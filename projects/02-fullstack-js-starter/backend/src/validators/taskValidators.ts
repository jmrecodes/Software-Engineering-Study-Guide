/**
 * FILE PURPOSE: Validate inputs for task-related endpoints.
 * LEARNING NOTE: Validators can sanitize inputs before controllers run.
 * TRY THIS: Add query parameter validation for pagination (page, pageSize).
 */

import { body, param } from 'express-validator';

export const taskIdParamValidator = [
  param('id').isUUID().withMessage('Task id must be a valid UUID.'),
];

export const createTaskValidator = [
  body('title')
    .isString()
    .withMessage('Title must be a string.')
    .trim()
    .notEmpty()
    .withMessage('Title is required.'),
  body('description')
    .optional()
    .isString()
    .withMessage('Description must be a string.'),
];

export const updateTaskValidator = [
  body('title').optional().isString().withMessage('Title must be a string.'),
  body('description').optional().isString().withMessage('Description must be a string.'),
  body('status')
    .optional()
    .isIn(['PENDING', 'IN_PROGRESS', 'COMPLETED'])
    .withMessage('Status must be one of PENDING, IN_PROGRESS, COMPLETED.'),
  body('isCompleted')
    .optional()
    .isBoolean()
    .withMessage('isCompleted must be a boolean.'),
];

// TODO: Challenge – validate dueDate when you add scheduling features.
