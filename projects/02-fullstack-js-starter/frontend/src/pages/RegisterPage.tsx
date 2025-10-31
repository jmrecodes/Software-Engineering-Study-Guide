/**
 * FILE PURPOSE: Provide a registration form that talks to /auth/register.
 * LEARNING NOTE: Client-side validation improves UX before hitting the server.
 * TRY THIS: Add password confirmation logic and show helpful hints.
 */

import type { JSX } from 'react';
import { ChangeEvent, FormEvent, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';

import { useAuth } from '../hooks/useAuth';
import apiClient from '../services/apiClient';
import styles from '../styles/AuthPage.module.css';

function RegisterPage(): JSX.Element {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setError(null);

    if (!name || !email || !password) {
      setError('Please fill all fields.');
      return;
    }

    setIsSubmitting(true);

    try {
      const response = await apiClient.post('/auth/register', { name, email, password });
      const { token, user } = response.data;
      login(user, token);
      navigate('/tasks');
    } catch (submissionError) {
      setError('Registration failed. Email might already exist.');
      console.error(submissionError);
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className={styles.wrapper}>
      <h1 className={styles.heading}>Create your account</h1>
      <p className={styles.subheading}>LEARNING NOTE: Prisma enforces email uniqueness—try registering twice.</p>
      <form className={styles.form} onSubmit={handleSubmit}>
        <label className={styles.label}>
          Name
          <input
            className={styles.input}
            value={name}
            onChange={(event: ChangeEvent<HTMLInputElement>) => setName(event.target.value)}
            placeholder="Your name"
          />
        </label>
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
          {isSubmitting ? 'Registering…' : 'Register'}
        </button>
      </form>
      <p className={styles.footerText}>
        Already have an account? <Link to="/login">Login</Link>
      </p>
    </div>
  );
}

export default RegisterPage;

// TODO: Challenge – add password visibility toggle icon.
