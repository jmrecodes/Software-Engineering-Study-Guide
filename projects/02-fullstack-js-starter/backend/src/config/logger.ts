/**
 * FILE PURPOSE: Create a simple console-based logger with consistent formatting.
 * LEARNING NOTE: Centralizing logging makes it easy to swap with Winston or Pino later.
 * TRY THIS: Adjust LOG_LEVEL in the env file to filter messages.
 */

import util from 'node:util';

import { validateEnv } from './env';

// Define log levels in ascending order of severity.
const LOG_LEVELS = ['debug', 'info', 'warn', 'error'] as const;

type LogLevel = (typeof LOG_LEVELS)[number];

type LogMethod = (message: string, metadata?: unknown) => void;

/**
 * FUNCTION: shouldLog
 * WHAT: Determines if the current message should be logged based on configured level.
 * WHY: Avoids cluttering the console when learners turn off verbose output.
 * RETURNS: boolean
 */
function shouldLog(requestedLevel: LogLevel, envLevel: LogLevel): boolean {
  return LOG_LEVELS.indexOf(requestedLevel) >= LOG_LEVELS.indexOf(envLevel);
}

const env = validateEnv();

/** Logger Implementation */
export const logger: Record<LogLevel, LogMethod> = {
  debug: (message, metadata) => {
    if (!shouldLog('debug', env.LOG_LEVEL)) return;
    console.debug(`🟦 DEBUG: ${message}`, serialize(metadata));
  },
  info: (message, metadata) => {
    if (!shouldLog('info', env.LOG_LEVEL)) return;
    console.info(`🟩 INFO: ${message}`, serialize(metadata));
  },
  warn: (message, metadata) => {
    if (!shouldLog('warn', env.LOG_LEVEL)) return;
    console.warn(`🟨 WARN: ${message}`, serialize(metadata));
  },
  error: (message, metadata) => {
    if (!shouldLog('error', env.LOG_LEVEL)) return;
    console.error(`🟥 ERROR: ${message}`, serialize(metadata));
  },
};

/**
 * FUNCTION: serialize
 * WHAT: Converts metadata into a readable string. Utilized for logging objects cleanly.
 * WHY: util.inspect prevents `[object Object]` from flooding logs.
 * RETURNS: string | undefined
 */
function serialize(metadata?: unknown): string | undefined {
  if (metadata === undefined) {
    return undefined;
  }
  return util.inspect(metadata, { depth: 4, colors: false, breakLength: 120 });
}

// TODO: Experiment – redirect logs to a file by replacing console.* calls.
