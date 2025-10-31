/**
 * FILE PURPOSE: Inject friendly console hints when LEARNING_MODE is enabled.
 * LEARNING NOTE: Middleware can enrich request objects or trigger side effects.
 * TRY THIS: Customize the hints array with your own debugging tips.
 */

import { NextFunction, Request, Response } from 'express';

const HINTS = [
  'CHALLENGE: Trace this request through controllers and services.',
  'TIP: Open Prisma Studio to see database changes live.',
  'EXPERIMENT: Add query logging by enabling Prisma middleware.',
] as const;

export function learningModeMiddleware(enabled: boolean) {
  return (req: Request, _res: Response, next: NextFunction): void => {
    if (enabled) {
      const hint = HINTS[Math.floor(Math.random() * HINTS.length)];
      console.log(`🎓 LEARNING MODE (${req.method} ${req.path}): ${hint}`);
    }

    next();
  };
}

// TODO: Challenge – send hints to response headers so the frontend can display them.
