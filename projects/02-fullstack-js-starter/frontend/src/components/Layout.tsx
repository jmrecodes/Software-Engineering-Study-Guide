/**
 * FILE PURPOSE: Provide a reusable layout with header/footer and navigation links.
 * LEARNING NOTE: Components receive `children` to render nested content.
 * TRY THIS: Add a dark mode toggle button that updates CSS variables.
 */

import type { JSX, PropsWithChildren } from 'react';
import { Link, useNavigate } from 'react-router-dom';

import { useAuthContext } from '../contexts/AuthContext';
import styles from '../styles/Layout.module.css';

function Layout({ children }: PropsWithChildren): JSX.Element {
  const { user, logout } = useAuthContext();
  const navigate = useNavigate();

  const handleLogout = (): void => {
    logout();
    navigate('/login');
  };

  return (
    <div className={styles.container}>
      <header className={styles.header}>
        <Link to="/tasks" className={styles.brand}>
          Starter Kit Task Manager
        </Link>
        <nav className={styles.nav}>
          {user ? (
            <>
              <span className={styles.userGreeting}>Hi, {user.name ?? 'Learner'}! 👋</span>
              <button type="button" className={styles.navButton} onClick={handleLogout}>
                Logout
              </button>
            </>
          ) : (
            <>
              <Link to="/login" className={styles.navLink}>
                Login
              </Link>
              <Link to="/register" className={styles.navLink}>
                Register
              </Link>
            </>
          )}
        </nav>
      </header>
      <main className={styles.main}>{children}</main>
      <footer className={styles.footer}>
        💡 LEARNING NOTE: Inspect the network tab when you interact with tasks.
      </footer>
    </div>
  );
}

export default Layout;

// TODO: Challenge – display flash messages in the layout when API calls succeed or fail.
