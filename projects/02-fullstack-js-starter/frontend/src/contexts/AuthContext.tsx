/**
 * FILE PURPOSE: Manage authentication state (user + token) via React Context.
 * LEARNING NOTE: Context avoids prop drilling across many components.
 * TRY THIS: Persist state in cookies instead of localStorage for comparison.
 */

import type { JSX, ReactNode } from 'react';
import { createContext, useContext, useEffect, useMemo, useReducer } from 'react';

type AuthState = {
  user: {
    id: string;
    email: string;
    name?: string;
  } | null;
  token: string | null;
  isLoading: boolean;
};

type AuthAction =
  | { type: 'LOGIN_SUCCESS'; payload: { user: AuthState['user']; token: string } }
  | { type: 'LOGOUT' }
  | { type: 'RESTORE_SESSION'; payload: { user: AuthState['user']; token: string } | null };

const initialState: AuthState = {
  user: null,
  token: null,
  isLoading: true,
};

function authReducer(state: AuthState, action: AuthAction): AuthState {
  switch (action.type) {
    case 'LOGIN_SUCCESS':
      return { user: action.payload.user, token: action.payload.token, isLoading: false };
    case 'LOGOUT':
      return { user: null, token: null, isLoading: false };
    case 'RESTORE_SESSION':
      if (!action.payload) {
        return { user: null, token: null, isLoading: false };
      }
      return { ...action.payload, isLoading: false };
    default:
      return state;
  }
}

interface AuthContextValue extends AuthState {
  login: (user: AuthState['user'], token: string) => void;
  logout: () => void;
}

const AuthContext = createContext<AuthContextValue | undefined>(undefined);

/**
 * COMPONENT: AuthProvider
 * WHAT: Wraps application tree and provides auth state via context.
 * WHY: Central place to handle persistence and token changes.
 */
export function AuthProvider({ children }: { children: ReactNode }): JSX.Element {
  const [state, dispatch] = useReducer(authReducer, initialState);

  // Restore session on mount from localStorage.
  useEffect(() => {
    const stored = localStorage.getItem('starter-kit-auth');
    if (!stored) {
      dispatch({ type: 'RESTORE_SESSION', payload: null });
      return;
    }

    try {
      const parsed = JSON.parse(stored) as { user: AuthState['user']; token: string };
      dispatch({ type: 'RESTORE_SESSION', payload: parsed });
    } catch (error) {
      console.warn('Failed to parse stored session', error);
      localStorage.removeItem('starter-kit-auth');
      dispatch({ type: 'RESTORE_SESSION', payload: null });
    }
  }, []);

  const login = (user: AuthState['user'], token: string): void => {
    localStorage.setItem('starter-kit-auth', JSON.stringify({ user, token }));
    dispatch({ type: 'LOGIN_SUCCESS', payload: { user, token } });
  };

  const logout = (): void => {
    localStorage.removeItem('starter-kit-auth');
    dispatch({ type: 'LOGOUT' });
  };

  const value = useMemo<AuthContextValue>(
    () => ({
      ...state,
      login,
      logout,
    }),
    [state],
  );

  if (state.isLoading) {
    return <div>Loading session…</div>;
  }

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

// eslint-disable-next-line react-refresh/only-export-components -- This hook intentionally lives alongside the provider for clarity.
export function useAuthContext(): AuthContextValue {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuthContext must be used within AuthProvider');
  }
  return context;
}

// TODO: Challenge – store session in indexedDB for offline resilience.
