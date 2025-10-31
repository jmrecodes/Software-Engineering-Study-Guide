# 07 · Debugging Guide

Debugging is detective work. This guide teaches you the tools and mindsets for solving issues efficiently.

---

## Developer Tools Tutorial

### Chrome DevTools
- **Elements Tab:** Inspect DOM structure and styles.
- **Console Tab:** View logs and errors. Use `console.table(tasks)` to visualize arrays.
- **Network Tab:** Monitor HTTP requests; filter by `XHR` to focus on API calls.
- **Sources Tab:** Set breakpoints in bundled code (Vite keeps source maps for clarity).

### Network Tab Analysis Tips
- Click a request → check **Headers** (URL, method, status).
- Inspect **Payload** to confirm request body matches expectations.
- Read **Response** for server messages.
- Use **Timing** to detect slow responses.

### Console Debugging Techniques
- Log descriptive messages, e.g., `console.log("[TaskList] fetched", tasks)`.
- Use `console.warn` for potential issues.
- Clean up logs once resolved to avoid noise.

## Backend Debugging

### Smart `console.log`
- Log structured objects: `console.log({ route: req.path, body: req.body });`.
- Use colors by prefixing emojis (`console.log("🟢 Service", data);`).

### VS Code Debugger
1. Open `backend/.vscode/launch.json` (provided) and start “Debug Express Server”.
2. Set breakpoints in TypeScript files.
3. Hit endpoints; VS Code pauses execution for inspection.

### Understanding Stack Traces
- Read from top to bottom; the top lines show where the error occurred.
- Identify your code vs library code; focus on files inside `src/`.
- Use error middleware to format and log stack traces clearly.

## Common Error Messages Decoded

| Error | Meaning | Fix |
|-------|---------|-----|
| `Cannot GET /...` | Route missing or method mismatch | Check Express routes & frontend URLs |
| `CORS error` | Browser blocked cross-origin request | Verify CORS middleware origin list |
| `Unexpected token in JSON` | Malformed JSON payload | Validate JSON syntax before sending |
| `Cannot read property of undefined` | Accessing nested property without checking existence | Use optional chaining or guard clauses |

**Tip:** When stuck, change one thing at a time, observe, and keep notes. Debugging is iterative learning.
