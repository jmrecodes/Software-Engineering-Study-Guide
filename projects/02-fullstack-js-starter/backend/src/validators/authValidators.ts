/**
 * FILE PURPOSE: Define express-validator chains for auth routes.
 * LEARNING NOTE: Validation ensures downstream code receives predictable values.
 * TRY THIS: Add password complexity checks and helpful error messages.
 */

import { body } from 'express-validator';

export const registerValidator = [
  body('email')
    .isEmail()
    .withMessage('Email must be valid.')
    .normalizeEmail(),
  body('password')
    .isLength({ min: 8 })
    .withMessage('Password must be at least 8 characters long.')
    .matches(/[A-Z]/)
    .withMessage('Password requires an uppercase letter.')
    .matches(/[a-z]/)
    .withMessage('Password requires a lowercase letter.')
    .matches(/[0-9]/)
    .withMessage('Password requires a digit.'),
  body('name').isString().trim().notEmpty().withMessage('Name is required.'),
];

export const loginValidator = [
  body('email').isEmail().withMessage('Email must be valid.').normalizeEmail(),
  body('password').isString().withMessage('Password is required.'),
];

// TODO: Challenge – throttle login attempts based on IP to slow brute-force attacks.
