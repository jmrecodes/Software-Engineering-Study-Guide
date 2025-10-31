/**
 * FILE PURPOSE: Define authentication-related HTTP routes such as /register and /login.
 * LEARNING NOTE: Routes are thin—validation and logic live in middleware and controllers.
 * TRY THIS: Add a `/me` route that returns the authenticated user's profile.
 */

import { Router } from 'express';

import { registerController, loginController } from '../controllers/authController';
import { runValidation } from '../middleware/validationMiddleware';
import { loginValidator, registerValidator } from '../validators/authValidators';

export const authRouter = Router();

// POST /api/auth/register
authRouter.post('/register', registerValidator, runValidation, registerController);

// POST /api/auth/login
authRouter.post('/login', loginValidator, runValidation, loginController);

// TODO: Challenge – add password reset routes using tokens emailed to users.
