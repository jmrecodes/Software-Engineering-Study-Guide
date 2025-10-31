/**
 * FILE PURPOSE: Convenience hook to access AuthContext with friendly naming.
 * LEARNING NOTE: Custom hooks hide repetitive logic while staying composable.
 * TRY THIS: Add analytics logging when login state changes.
 */

import { useAuthContext } from '../contexts/AuthContext';

export const useAuth = useAuthContext;
