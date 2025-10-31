/**
 * FILE PURPOSE: Bootstraps the Express server, ensuring environment variables are valid before listening for requests.
 * LEARNING NOTE: This is the best entry point to place breakpoints when debugging startup issues.
 * TRY THIS: Update the LOG_LEVEL in the env file and watch how the logger output changes.
 */

// Import Explanation: dotenv config loads environment variables from .env into process.env.
import 'dotenv/config';

// Import Explanation: createServer centralizes Express app creation.
import { createServer } from './server';

// Import Explanation: validateEnv ensures we fail fast if required env vars are missing.
import { validateEnv } from './config/env';

// Import Explanation: logger gives us consistent, color-coded console output.
import { logger } from './config/logger';

// Validate environment variables before anything else to avoid runtime surprises.
const env = validateEnv();

/**
 * FUNCTION: bootstrap
 * WHAT: Creates the Express app, attaches listeners, and starts the HTTP server.
 * WHY: Keep startup logic isolated for easier testing and future enhancements (e.g., graceful shutdown).
 * RETURNS: Promise<void> to allow async setup steps (database connections, messaging queues, etc.).
 */
async function bootstrap(): Promise<void> {
  // LEARNING NOTE: createServer configures middleware, routes, and error handlers.
  const app = await createServer();

  // Start listening on the configured port.
  app.listen(env.PORT, () => {
    logger.info(`🚀 Server listening on port ${env.PORT}`);
    logger.info('LEARNING NOTE: Try hitting /api/health in your browser to confirm the server is running.');
  });
}

// Execute bootstrap and catch any synchronous/asynchronous startup errors.
bootstrap().catch((error: unknown) => {
  logger.error('❌ Failed to start server', error);
  process.exit(1); // Explicit exit so CI pipelines fail fast.
});

// TODO: Challenge – implement graceful shutdown to close database connections on SIGTERM.
