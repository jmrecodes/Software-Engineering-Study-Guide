# Backend Learning Guide

This directory hosts the Express + TypeScript API. Every file includes commentary to explain its role and invite experiments.

## Key Concepts Covered
- Environment variable validation
- Layered architecture (routes → controllers → services → Prisma)
- Authentication with JSON Web Tokens (JWT)
- Input validation using `express-validator`
- Error handling and logging best practices

## Scripts
```bash
npm install        # Install dependencies
npm run dev        # Start development server with hot reload
npm run build      # Compile TypeScript to JavaScript (output in dist/)
npm run start      # Run compiled JavaScript
npm run lint       # Analyze code style issues (ESLint)
npm run prisma:migrate  # Run Prisma migrations
npm run prisma:studio   # Open Prisma Studio data viewer
npm run prisma:seed     # Seed demo data (invokes prisma/seed.ts)
```

## Architecture Overview
```
src/
  config/        # Environment management and shared config
  middleware/    # Express middleware (auth, errors, logging, validation)
  routes/        # Route definitions grouping API endpoints
  controllers/   # Request handlers with high-level orchestration
  services/      # Business logic interacting with Prisma
  validators/    # express-validator schemas
  utils/         # Supporting helpers (password hashing, tokens)
  prismaClient.ts# Centralized Prisma instance
```

## Quick Start
1. Copy `.env.example` to `.env` and fill in your PostgreSQL connection string, JWT secret, and port.
2. Install dependencies with `npm install` and generate the Prisma client via `npx prisma generate` (automatically handled by `npm install`).
3. Apply the baseline schema using `npm run prisma:migrate` and seed sample data with `npm run prisma:seed` (optional but recommended for the tutorials).
4. Start the API in watch mode using `npm run dev`; the server boots on `http://localhost:4000` by default.
5. Hit `GET /api/health` to verify uptime, then follow the learning path below to explore deeper.

## Progressive Learning
- Start at `src/index.ts` to see the server bootstrap sequence and logging setup.
- Follow the comments in `src/server.ts` to understand middleware ordering and route registration.
- Explore `src/routes` and trace a request into the controllers and services layer.
- Open the `tutorials/` directory for deep dives and annotated examples.

> **TRY THIS:** Clone `authService.ts` into `authService.playground.ts` and break things intentionally. Observe TypeScript warnings and error handling behaviors.
