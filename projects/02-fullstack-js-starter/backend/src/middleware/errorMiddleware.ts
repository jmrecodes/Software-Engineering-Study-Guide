/**
 * FILE PURPOSE: Centralized Express error handling to return consistent responses.
 * LEARNING NOTE: Error middleware has four parameters (err, req, res, next) and must be last in the chain.
 * TRY THIS: Format errors differently based on NODE_ENV (detailed vs minimal).
 */

import { NextFunction, Request, Response } from 'express';
import { StatusCodes } from 'http-status-codes';

import { logger } from '../config/logger';

// eslint-disable-next-line @typescript-eslint/no-explicit-any -- Express error handler allows any type.
export function errorHandler(err: any, _req: Request, res: Response, _next: NextFunction): void {
  logger.error('Unhandled error captured by middleware', err);

  const status = err.statusCode ?? StatusCodes.INTERNAL_SERVER_ERROR;
  const message = err.message ?? 'Something went wrong';

  res.status(status).json({
    message,
    // LEARNING NOTE: Avoid sending stack traces in production; they may leak sensitive info.
    stack: process.env.NODE_ENV === 'development' ? err.stack : undefined,
  });
}

// TODO: Challenge – integrate with monitoring tools like Sentry.
