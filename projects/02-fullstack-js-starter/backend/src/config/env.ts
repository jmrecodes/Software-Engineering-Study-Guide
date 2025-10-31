/**
 * FILE PURPOSE: Centralized environment variable schema validation and typed accessors.
 * LEARNING NOTE: Validating env vars early prevents confusing runtime failures later.
 * TRY THIS: Temporarily remove PORT from .env and observe the startup error message.
 */

import { z } from 'zod';

// Import Explanation: We use Zod to define and validate the environment schema succinctly.
const EnvSchema = z.object({
  PORT: z.coerce.number().min(1).max(65535).default(4000),
  DATABASE_URL: z.string().url('DATABASE_URL must be a valid URL'),
  JWT_SECRET: z.string().min(16, 'JWT_SECRET should be at least 16 characters long'),
  CLIENT_URL: z.string().url('CLIENT_URL must be a valid URL'),
  LOG_LEVEL: z.enum(['debug', 'info', 'warn', 'error']).default('info'),
  LEARNING_MODE: z.enum(['on', 'off']).default('on'),
});

// Exported type for strongly typed configuration usage.
export type EnvConfig = z.infer<typeof EnvSchema>;

let cachedEnv: EnvConfig | null = null;

/**
 * FUNCTION: validateEnv
 * WHAT: Parses process.env against the schema and memoizes the result.
 * WHY: Avoid repeated parsing while ensuring other modules access a trusted config object.
 * RETURNS: EnvConfig containing validated configuration values.
 */
export function validateEnv(): EnvConfig {
  if (cachedEnv) {
    return cachedEnv;
  }

  const parsed = EnvSchema.safeParse(process.env);

  if (!parsed.success) {
    console.error('Environment validation failed:', parsed.error.format());
    throw new Error('Invalid environment variables. Fix .env and restart the server.');
  }

  cachedEnv = parsed.data;
  return cachedEnv;
}

// TODO: Challenge – load environment overrides based on NODE_ENV (development/test/production).
