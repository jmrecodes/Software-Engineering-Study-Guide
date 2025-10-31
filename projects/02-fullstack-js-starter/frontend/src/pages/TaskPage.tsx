/**
 * FILE PURPOSE: Main authenticated experience showing task creation and list.
 * LEARNING NOTE: Combines custom hooks, components, and conditional rendering.
 * TRY THIS: Add filters (tabs) to view tasks by status.
 */

import type { JSX } from 'react';

import TaskForm from '../components/TaskForm';
import TaskList from '../components/TaskList';
import { useTasks } from '../hooks/useTasks';
import styles from '../styles/TaskPage.module.css';

function TaskPage(): JSX.Element {
  const { tasks, isLoading, error, createTask, completeTask, deleteTask } = useTasks();

  return (
    <div className={styles.page}>
      <div className={styles.grid}>
        <TaskForm onCreate={createTask} />
        <section className={styles.listSection}>
          {isLoading && <p className={styles.info}>Loading tasks…</p>}
          {error && <p className={styles.error}>{error}</p>}
          {!isLoading && <TaskList tasks={tasks} onComplete={completeTask} onDelete={deleteTask} />}
        </section>
      </div>
      <aside className={styles.sidebar}>
        <h2 className={styles.sidebarHeading}>Learning Nuggets</h2>
        <ul className={styles.sidebarList}>
          <li>LEARNING NOTE: Custom hooks keep components lean.</li>
          <li>TRY THIS: Add suspense fallback around TaskList.</li>
          <li>DID YOU KNOW: React batches state updates inside event handlers for performance.</li>
        </ul>
      </aside>
    </div>
  );
}

export default TaskPage;

// TODO: Challenge – add drag-and-drop reordering with libraries like dnd-kit.
