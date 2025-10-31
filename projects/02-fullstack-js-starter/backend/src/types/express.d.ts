/**
 * FILE PURPOSE: Augment Express Request type with a user property for authenticated routes.
 * LEARNING NOTE: Module augmentation keeps TypeScript aware of custom properties added via middleware.
 * TRY THIS: Add additional fields (e.g., roles) after expanding JWT payloads.
 */

import 'express';

declare module 'express-serve-static-core' {
  interface Request {
    user?: {
      id: string;
      email: string;
      name?: string;
    };
  }
}
