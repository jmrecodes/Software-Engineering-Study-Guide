/**
 * FILE PURPOSE: Compose the Express application by wiring middleware, routes, and error handlers.
 * LEARNING NOTE: Order matters! Follow the comments to understand execution flow.
 * TRY THIS: Toggle LEARNING_MODE in .env to see how verbose comments influence runtime hints.
 */

import express, { Application, Request, Response } from 'express';
import helmet from 'helmet';
import cors from 'cors';
import morgan from 'morgan';

import { validateEnv } from './config/env';
import { logger } from './config/logger';
import { registerRoutes } from './routes';
import { errorHandler } from './middleware/errorMiddleware';
import { learningModeMiddleware } from './middleware/learningModeMiddleware';

/**
 * FUNCTION: createServer
 * WHAT: Initializes an Express Application instance with all project middleware.
 * WHY: Centralizing app creation simplifies testing and keeps index.ts focused on startup.
 * RETURNS: Promise<Application> because future enhancements may involve async setup (e.g., DB connections).
 */
export async function createServer(): Promise<Application> {
  const env = validateEnv();
  const app = express();

  // 🔐 SECURITY FIRST: Helmet sets helpful HTTP headers.
  app.use(helmet());

  // 🌐 CORS: Allow frontend domain to call the API.
  app.use(
    cors({
      origin: env.CLIENT_URL,
      credentials: true,
    }),
  );

  // 🪵 LOGGING: Morgan logs request details; try switching formats for experimentation.
  app.use(
    morgan('dev', {
      stream: {
        write: (message: string): void => logger.info(message.trim()),
      },
    }),
  );

  // 🧠 LEARNING MODE: Injects runtime tips when enabled.
  app.use(learningModeMiddleware(env.LEARNING_MODE === 'on'));

  // 🧱 BODY PARSER: Parse JSON bodies before hitting validators/controllers.
  app.use(express.json());

  // 📢 HEALTH CHECK: Minimal endpoint to confirm availability.
  app.get('/api/health', (_req: Request, res: Response) => {
    res.status(200).json({ status: 'ok', timestamp: new Date().toISOString() });
  });

  // 🚏 ROUTES: Mount feature routes under /api.
  app.use('/api', registerRoutes());

  // ❗️ ERROR HANDLING: Always the last middleware to catch downstream errors.
  app.use(errorHandler);

  return app;
}

// TODO: Challenge – Add rate limiting middleware (e.g., express-rate-limit) to prevent abuse.
