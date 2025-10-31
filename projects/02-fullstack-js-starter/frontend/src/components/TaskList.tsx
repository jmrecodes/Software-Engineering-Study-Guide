/**
 * FILE PURPOSE: Display tasks with actions for completion, editing, and deletion.
 * LEARNING NOTE: React re-renders when props or state change—memoize heavy computations when needed.
 * TRY THIS: Implement inline editing to practice controlled components.
 */

import type { JSX } from 'react';
import { useMemo } from 'react';
import classNames from 'classnames';

import type { Task } from '../types/task';
import styles from '../styles/TaskList.module.css';

interface TaskListProps {
  tasks: Task[];
  onComplete: (id: string) => Promise<void>;
  onDelete: (id: string) => Promise<void>;
}

function TaskList({ tasks, onComplete, onDelete }: TaskListProps): JSX.Element {
  const completedCount = useMemo(() => tasks.filter((task) => task.isCompleted).length, [tasks]);

  if (tasks.length === 0) {
    return <p className={styles.emptyState}>No tasks yet. Create one to get rolling! 🚀</p>;
  }

  return (
    <section className={styles.wrapper}>
      <header className={styles.header}>
        <h2 className={styles.heading}>Your Tasks</h2>
        <p className={styles.meta}>
          Completed {completedCount} / {tasks.length}
        </p>
      </header>
      <ul className={styles.list}>
        {tasks.map((task) => (
          <li key={task.id} className={classNames(styles.item, { [styles.completed]: task.isCompleted })}>
            <div>
              <h3 className={styles.title}>{task.title}</h3>
              {task.description && <p className={styles.description}>{task.description}</p>}
              <p className={styles.timestamp}>
                Created: {new Date(task.createdAt).toLocaleString()} • Status: {task.status}
              </p>
            </div>
            <div className={styles.actions}>
              <button
                type="button"
                className={styles.actionButton}
                onClick={() => void onComplete(task.id)}
                disabled={task.isCompleted}
              >
                {task.isCompleted ? 'Completed' : 'Mark Complete'}
              </button>
              <button type="button" className={styles.dangerButton} onClick={() => void onDelete(task.id)}>
                Delete
              </button>
            </div>
          </li>
        ))}
      </ul>
    </section>
  );
}

export default TaskList;

// TODO: Challenge – add edit button to launch a modal with TaskForm pre-filled.
