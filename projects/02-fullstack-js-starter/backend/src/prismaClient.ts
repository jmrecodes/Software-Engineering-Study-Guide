/**
 * FILE PURPOSE: Export a singleton Prisma Client instance for reuse across services.
 * LEARNING NOTE: Creating multiple instances can exhaust database connections—singleton avoids that.
 * TRY THIS: Enable query logging by passing `{ log: ['query'] }` to PrismaClient constructor.
 */

import { PrismaClient } from '@prisma/client';

// Lazy initialization to support testing (e.g., swap with mock).
export const prisma = new PrismaClient();

// TODO: Challenge – add metrics by listening to Prisma middleware events.
