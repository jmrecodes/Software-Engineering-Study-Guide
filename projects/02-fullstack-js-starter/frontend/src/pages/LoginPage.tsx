/**
 * FILE PURPOSE: Provide a login form with client-side validation and API integration.
 * LEARNING NOTE: We use controlled inputs and async event handlers.
 * TRY THIS: Display password strength feedback under the input.
 */

import type { JSX } from 'react';
import { ChangeEvent, FormEvent, useState } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import type { Location } from 'react-router-dom';

import { useAuth } from '../hooks/useAuth';
import apiClient from '../services/apiClient';
import styles from '../styles/AuthPage.module.css';

function LoginPage(): JSX.Element {
  const navigate = useNavigate();
  const location = useLocation();
  const { login } = useAuth();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setError(null);

    if (!email || !password) {
      setError('Both email and password are required.');
      return;
    }

    setIsSubmitting(true);

    try {
      const response = await apiClient.post('/auth/login', { email, password });
      const { token, user } = response.data;
      login(user, token);

      const redirectTo = (location.state as { from?: Location })?.from?.pathname ?? '/tasks';
      navigate(redirectTo, { replace: true });
    } catch (submissionError) {
      setError('Login failed. Double-check credentials.');
      console.error(submissionError);
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className={styles.wrapper}>
      <h1 className={styles.heading}>Welcome Back!</h1>
      <p className={styles.subheading}>LEARNING NOTE: Inspect the network request after submitting this form.</p>
      <form className={styles.form} onSubmit={handleSubmit}>
        <label className={styles.label}>
          Email
          <input
            className={styles.input}
            type="email"
            value={email}
            onChange={(event: ChangeEvent<HTMLInputElement>) => setEmail(event.target.value)}
            placeholder="you@example.com"
          />
        </label>
        <label className={styles.label}>
          Password
          <input
            className={styles.input}
            type="password"
            value={password}
            onChange={(event: ChangeEvent<HTMLInputElement>) => setPassword(event.target.value)}
            placeholder="••••••••"
          />
        </label>
        {error && <p className={styles.error}>{error}</p>}
        <button className={styles.button} type="submit" disabled={isSubmitting}>
          {isSubmitting ? 'Logging in…' : 'Login'}
        </button>
      </form>
      <p className={styles.footerText}>
        New here? <Link to="/register">Create an account</Link>
      </p>
    </div>
  );
}

export default LoginPage;

// TODO: Challenge – remember email using localStorage for smoother UX.
