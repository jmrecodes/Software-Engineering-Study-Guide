/**
 * FILE PURPOSE: Guard routes so only authenticated users can access them.
 * LEARNING NOTE: Composition keeps route protection declarative.
 * TRY THIS: Redirect users back to their original destination after login.
 */

import type { JSX } from 'react';
import { Navigate, useLocation } from 'react-router-dom';

import { useAuth } from '../hooks/useAuth';

interface ProtectedRouteProps {
  children: JSX.Element;
}

export function ProtectedRoute({ children }: ProtectedRouteProps): JSX.Element {
  const { user } = useAuth();
  const location = useLocation();

  if (!user) {
    return <Navigate to="/login" state={{ from: location }} replace />;
  }

  return children;
}

// TODO: Challenge – display a loading skeleton while restoring sessions.
