/**
 * FILE PURPOSE: Run express-validator results and send structured responses on failure.
 * LEARNING NOTE: Validation is middleware so controllers only handle valid data.
 * TRY THIS: Include request-specific metadata (e.g., requestId) in error responses.
 */

import { NextFunction, Request, Response } from 'express';
import { validationResult } from 'express-validator';
import type { ValidationError } from 'express-validator';
import { StatusCodes } from 'http-status-codes';

export function runValidation(req: Request, res: Response, next: NextFunction): void {
  const errors = validationResult(req);

  if (!errors.isEmpty()) {
    const details = errors.array().map((error: ValidationError) => ({
      field: 'path' in error && typeof error.path === 'string' ? error.path : error.type,
      message: error.msg,
    }));

    res.status(StatusCodes.BAD_REQUEST).json({
      message: 'Validation failed',
      details,
    });
    return;
  }

  next();
}

// TODO: Challenge – internationalize validation messages for multi-lingual support.
