/**
 * FILE PURPOSE: Handle JSON Web Token (JWT) signing and verification.
 * LEARNING NOTE: Services centralize reusable logic—controllers rely on them.
 * TRY THIS: Add refresh token issuance and verification.
 */

import jwt, { JwtPayload } from 'jsonwebtoken';

import { validateEnv } from '../config/env';

const env = validateEnv();

interface TokenPayload {
  userId: string;
  email: string;
}

export class TokenService {
  /**
   * FUNCTION: signToken
   * WHAT: Creates a signed JWT for the client to store.
   * RETURNS: string containing the token.
   */
  signToken(payload: TokenPayload): string {
    return jwt.sign(payload, env.JWT_SECRET, { expiresIn: '1h' });
  }

  /**
   * FUNCTION: verifyToken
   * WHAT: Verifies a JWT and returns its payload if valid.
   * RETURNS: TokenPayload
   */
  verifyToken(token: string): TokenPayload {
    const decoded = jwt.verify(token, env.JWT_SECRET) as JwtPayload;

    if (!decoded.userId || !decoded.email) {
      throw new Error('Invalid token payload');
    }

    return {
      userId: decoded.userId as string,
      email: decoded.email as string,
    };
  }
}

// TODO: Challenge – allow custom expiration durations passed in as options.
