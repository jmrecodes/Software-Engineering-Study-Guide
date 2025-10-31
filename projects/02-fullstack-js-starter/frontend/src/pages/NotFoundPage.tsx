/**
 * FILE PURPOSE: Friendly 404 page for unknown routes.
 * LEARNING NOTE: Good UX includes helpful dead ends.
 * TRY THIS: Add a search box to guide users.
 */

import type { JSX } from 'react';
import { Link } from 'react-router-dom';

import styles from '../styles/NotFoundPage.module.css';

function NotFoundPage(): JSX.Element {
  return (
    <div className={styles.wrapper}>
      <h1 className={styles.heading}>404</h1>
      <p className={styles.message}>We couldn’t find that page.</p>
      <Link to="/tasks" className={styles.link}>
        Return to tasks
      </Link>
    </div>
  );
}

export default NotFoundPage;

// TODO: Challenge – add illustrations or helpful links to docs.
