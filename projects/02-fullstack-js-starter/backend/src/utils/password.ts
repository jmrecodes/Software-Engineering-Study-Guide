/**
 * FILE PURPOSE: Encapsulate password hashing and comparison using bcrypt.
 * LEARNING NOTE: Abstracting hashing logic makes it easy to swap algorithms later.
 * TRY THIS: Experiment with different salt rounds and observe performance impact.
 */

import bcrypt from 'bcrypt';

const SALT_ROUNDS = 10;

export class PasswordManager {
  /**
   * FUNCTION: hashPassword
   * WHAT: Hashes a plaintext password using bcrypt.
   * RETURNS: Promise<string> – the hashed password.
   */
  async hashPassword(plainPassword: string): Promise<string> {
    return bcrypt.hash(plainPassword, SALT_ROUNDS);
  }

  /**
   * FUNCTION: comparePassword
   * WHAT: Compares a plaintext password to a hashed password.
   * RETURNS: Promise<boolean> – whether the password matches.
   */
  async comparePassword(plainPassword: string, hash: string): Promise<boolean> {
    return bcrypt.compare(plainPassword, hash);
  }
}

// TODO: Challenge – introduce bcrypt peppering or Argon2 for stronger security.
