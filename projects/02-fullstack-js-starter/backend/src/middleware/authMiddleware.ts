/**
 * FILE PURPOSE: Protect routes by verifying JWT tokens and attaching user context.
 * LEARNING NOTE: Middleware executes before controllers—perfect place for cross-cutting concerns.
 * TRY THIS: Add role-based authorization once you extend the JWT payload.
 */

import { NextFunction, Request, Response } from 'express';
import { StatusCodes } from 'http-status-codes';

import { TokenService } from '../services/tokenService';
import { prisma } from '../prismaClient';

const tokenService = new TokenService();

/**
 * FUNCTION: authenticate
 * WHAT: Validates the Authorization header, verifies the token, and fetches a lightweight user object.
 * WHY: Ensures downstream controllers have access to req.user.
 * RETURNS: Promise<void> – middleware calls next() or sends response.
 */
export async function authenticate(req: Request, res: Response, next: NextFunction): Promise<void> {
  const header = req.headers.authorization;

  if (!header?.startsWith('Bearer ')) {
    res.status(StatusCodes.UNAUTHORIZED).json({ message: 'Missing or invalid Authorization header' });
    return;
  }

  const token = header.split(' ')[1];

  try {
    const payload = tokenService.verifyToken(token);

    // Fetch user details to expose name/email in req.user (avoid storing sensitive info in token).
    const user = await prisma.user.findUnique({
      where: { id: payload.userId },
      select: { id: true, email: true, name: true },
    });

    if (!user) {
      res.status(StatusCodes.UNAUTHORIZED).json({ message: 'User not found' });
      return;
    }

    req.user = user;
    next();
  } catch (error) {
    res.status(StatusCodes.UNAUTHORIZED).json({ message: 'Invalid or expired token' });
  }
}

// TODO: Challenge – cache user lookups to reduce database hits for repeated requests.
