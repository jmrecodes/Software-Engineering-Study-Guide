/**
 * FILE PURPOSE: Entry point that renders the React application into the DOM.
 * LEARNING NOTE: Vite handles bundling; this file bridges HTML and React.
 * TRY THIS: Wrap <App /> with React.StrictMode to surface additional warnings.
 */

import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';

import App from './App';
import { AuthProvider } from './contexts/AuthContext';

import './styles/global.css';

const rootElement = document.getElementById('root');

if (!rootElement) {
  throw new Error('Root element not found. Check index.html');
}

const root = ReactDOM.createRoot(rootElement);

root.render(
  <React.StrictMode>
    <BrowserRouter>
      <AuthProvider>
        <App />
      </AuthProvider>
    </BrowserRouter>
  </React.StrictMode>,
);

// TODO: Challenge – add a ThemeProvider to experiment with dark/light mode.
