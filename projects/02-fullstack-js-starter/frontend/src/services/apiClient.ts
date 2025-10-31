/**
 * FILE PURPOSE: Configure Axios instance with base URL, interceptors, and error logging.
 * LEARNING NOTE: Interceptors allow us to automatically attach tokens to every request.
 * TRY THIS: Add a response interceptor to refresh tokens on 401 responses.
 */

import axios, { AxiosError, AxiosResponse } from 'axios';
import type { AxiosRequestHeaders, InternalAxiosRequestConfig } from 'axios';

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL ?? 'http://localhost:4000/api',
  timeout: 10_000,
});

// Request interceptor attaches JWT token when available.
apiClient.interceptors.request.use((config: InternalAxiosRequestConfig) => {
  const stored = localStorage.getItem('starter-kit-auth');
  if (!stored) {
    return config;
  }

  try {
    const { token } = JSON.parse(stored) as { token: string };
    if (token) {
      if (config.headers && typeof config.headers.set === 'function') {
        config.headers.set('Authorization', `Bearer ${token}`);
      } else {
        const existingHeaders = (config.headers ?? {}) as AxiosRequestHeaders;
        config.headers = {
          ...existingHeaders,
          Authorization: `Bearer ${token}`,
        } as AxiosRequestHeaders;
      }
    }
  } catch (error) {
    console.warn('Failed to parse stored auth token', error);
  }

  return config;
});

// Response interceptor for logging errors in a consistent way.
apiClient.interceptors.response.use(
  (response: AxiosResponse) => response,
  (error: AxiosError) => {
    console.error('API error captured by interceptor', error);
    return Promise.reject(error);
  },
);

export default apiClient;
