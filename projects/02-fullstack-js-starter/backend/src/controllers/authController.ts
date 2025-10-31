/**
 * FILE PURPOSE: Handle HTTP requests for user registration and login.
 * LEARNING NOTE: Controllers orchestrate services, leaving business logic elsewhere.
 * TRY THIS: Return additional profile data from loginController and display it on the frontend dashboard.
 */

import { Request, Response } from 'express';
import { StatusCodes } from 'http-status-codes';

import { AuthService } from '../services/authService';
import { TokenService } from '../services/tokenService';
import { logger } from '../config/logger';

const authService = new AuthService();
const tokenService = new TokenService();

/**
 * FUNCTION: registerController
 * WHAT: Validates request data (already sanitized), creates a user, and returns a JWT.
 * WHY: Keep HTTP concerns (status codes, response shape) centralized here.
 * RETURNS: Promise<void> because Express handles response sending within the function.
 */
export async function registerController(req: Request, res: Response): Promise<void> {
  const { email, password, name } = req.body;

  try {
    const user = await authService.register({ email, password, name });
    const token = tokenService.signToken({ userId: user.id, email: user.email });

    res.status(StatusCodes.CREATED).json({
      message: 'Registration successful',
      token,
      user: {
        id: user.id,
        email: user.email,
        name: user.name,
      },
    });
  } catch (error) {
    logger.error('registerController error', error);
    res.status(StatusCodes.CONFLICT).json({
      message: 'Email already in use or invalid input',
    });
  }
}

/**
 * FUNCTION: loginController
 * WHAT: Authenticates a user and issues a JWT.
 * WHY: Separate from service so we can tweak HTTP response format without altering core logic.
 * RETURNS: Promise<void>
 */
export async function loginController(req: Request, res: Response): Promise<void> {
  const { email, password } = req.body;

  try {
    const user = await authService.login({ email, password });
    const token = tokenService.signToken({ userId: user.id, email: user.email });

    res.status(StatusCodes.OK).json({
      message: 'Login successful',
      token,
      user: {
        id: user.id,
        email: user.email,
        name: user.name,
      },
    });
  } catch (error) {
    logger.warn('loginController failed login attempt', { email });
    res.status(StatusCodes.UNAUTHORIZED).json({
      message: 'Invalid email or password',
    });
  }
}

// TODO: Challenge – return refresh tokens and implement rotation for enhanced security.
