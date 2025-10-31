/**
 * FILE PURPOSE: Present a controlled form for creating new tasks.
 * LEARNING NOTE: Controlled inputs keep React state as the source of truth.
 * TRY THIS: Add field-level validation messages or success animations.
 */

import type { JSX } from 'react';
import { ChangeEvent, FormEvent, useState } from 'react';

import styles from '../styles/TaskForm.module.css';

interface TaskFormProps {
  onCreate: (payload: { title: string; description?: string }) => Promise<void>;
}

function TaskForm({ onCreate }: TaskFormProps): JSX.Element {
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [error, setError] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();

    if (!title.trim()) {
      setError('Title is required.');
      return;
    }

    setError(null);
    setIsSubmitting(true);

    try {
      await onCreate({ title: title.trim(), description: description.trim() || undefined });
      setTitle('');
      setDescription('');
    } catch (submissionError) {
      setError('Failed to create task. Check console for details.');
      console.error(submissionError);
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <form className={styles.form} onSubmit={handleSubmit}>
      <h2 className={styles.heading}>Add a Task</h2>
      <label className={styles.label}>
        Title
        <input
          className={styles.input}
          value={title}
          onChange={(event: ChangeEvent<HTMLInputElement>) => setTitle(event.target.value)}
          placeholder="e.g., Ship feature X"
        />
      </label>
      <label className={styles.label}>
        Description
        <textarea
          className={styles.textarea}
          value={description}
          onChange={(event: ChangeEvent<HTMLTextAreaElement>) => setDescription(event.target.value)}
          placeholder="Why is this task important?"
        />
      </label>
      {error && <p className={styles.error}>{error}</p>}
      <button className={styles.button} type="submit" disabled={isSubmitting}>
        {isSubmitting ? 'Creating…' : 'Create Task'}
      </button>
      <p className={styles.tip}>LEARNING NOTE: This form uses controlled components—React manages the input state.</p>
    </form>
  );
}

export default TaskForm;

// TODO: Challenge – add a select input for task priority and include it in the payload.
