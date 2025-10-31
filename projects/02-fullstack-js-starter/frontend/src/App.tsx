/**
 * FILE PURPOSE: Define top-level routing and layout structure.
 * LEARNING NOTE: React Router v6 renders the first matching route inside <Routes>.
 * TRY THIS: Add lazy-loaded routes with React.Suspense for code-splitting.
 */

import type { JSX } from 'react';
import { Route, Routes, Navigate } from 'react-router-dom';

import Layout from './components/Layout';
import { ProtectedRoute } from './routes/ProtectedRoute';
import LoginPage from './pages/LoginPage';
import RegisterPage from './pages/RegisterPage';
import TaskPage from './pages/TaskPage';
import NotFoundPage from './pages/NotFoundPage';

function App(): JSX.Element {
  return (
    <Layout>
      <Routes>
        <Route path="/" element={<Navigate to="/tasks" replace />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="/register" element={<RegisterPage />} />
        <Route
          path="/tasks"
          element={
            <ProtectedRoute>
              <TaskPage />
            </ProtectedRoute>
          }
        />
        <Route path="*" element={<NotFoundPage />} />
      </Routes>
    </Layout>
  );
}

export default App;

// TODO: Challenge – add an About page routed at /about with project credits.
