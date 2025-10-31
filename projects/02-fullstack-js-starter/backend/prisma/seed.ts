/**
 * FILE PURPOSE: Seed the database with initial users and tasks to explore immediately.
 * LEARNING NOTE: Run `npx prisma db seed` to execute this file. Adjust data to experiment.
 * TRY THIS: Add more tasks or users to observe how relations appear in Prisma Studio.
 */
/// <reference types="node" />

import { PrismaClient } from '@prisma/client';

// Import Explanation:
// PrismaClient connects to the configured database and provides typed access to models.
const prisma = new PrismaClient();

async function main(): Promise<void> {
  /**
   * FUNCTION: main
   * WHAT: Creates a demo user and a couple of sample tasks.
   * WHY: So learners can log in immediately and inspect task flows.
   * RETURNS: Promise<void> because the script exits after completion.
   */

  // We use upsert to avoid duplicate inserts if the seed runs multiple times.
  const user = await prisma.user.upsert({
    where: { email: 'demo@example.com' },
    update: {},
    create: {
      name: 'Demo Learner',
      email: 'demo@example.com',
      passwordHash: '$2b$10$K0wP9oPuhwzfmVul4y5ECuV6Y.qqwJY9Vc3rroWAt4EvsC0Bpvyz.', // bcrypt hash for "password123"
      tasks: {
        create: [
          {
            title: 'Read the backend tutorial',
            description: 'Focus on understanding the middleware order.',
          },
          {
            title: 'Complete the first exercise',
            description: 'Add a new GET /tasks/:id endpoint.',
            status: 'IN_PROGRESS',
          },
        ],
      },
    },
  });

  console.log('Seeded user with id:', user.id);
}

main()
  .catch((error) => {
    console.error('Seed error:', error);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
