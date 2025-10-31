/**
 * FILE PURPOSE: Encapsulate authentication business logic (registration + login).
 * LEARNING NOTE: Services are pure business logic—no HTTP concerns.
 * TRY THIS: Add password strength checks here for better security.
 */

import { PrismaClient, User } from '@prisma/client';

import { prisma } from '../prismaClient';
import { PasswordManager } from '../utils/password';

const prismaClient: PrismaClient = prisma;

export interface RegisterInput {
  email: string;
  password: string;
  name: string;
}

export interface LoginInput {
  email: string;
  password: string;
}

export class AuthService {
  private passwordManager = new PasswordManager();

  /**
   * FUNCTION: register
   * WHAT: Hashes password and persists a new user record.
   * WHY: Keep hashing logic centralized so controllers remain thin.
   * RETURNS: Promise<User>
   */
  async register(input: RegisterInput): Promise<User> {
    const passwordHash = await this.passwordManager.hashPassword(input.password);

    // LEARNING NOTE: Prisma throws if email is not unique. Catching happens in the controller.
    return prismaClient.user.create({
      data: {
        email: input.email,
        name: input.name,
        passwordHash,
      },
    });
  }

  /**
   * FUNCTION: login
   * WHAT: Verifies user credentials.
   * RETURNS: Promise<User>
   */
  async login(input: LoginInput): Promise<User> {
    const user = await prismaClient.user.findUnique({ where: { email: input.email } });

    if (!user) {
      throw new Error('Invalid credentials');
    }

    const isValid = await this.passwordManager.comparePassword(input.password, user.passwordHash);

    if (!isValid) {
      throw new Error('Invalid credentials');
    }

    return user;
  }
}

// TODO: Challenge – Track failed login attempts and lock accounts after repeated failures.
