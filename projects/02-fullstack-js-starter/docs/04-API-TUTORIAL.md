# 04 · API Tutorial

This tutorial demystifies REST, HTTP methods, and how to test endpoints confidently.

---

## Understanding REST

### HTTP Methods with Analogies
- **GET** (`/api/tasks`): Read data – like browsing a library catalogue.
- **POST** (`/api/tasks`): Create data – like adding a new book to the shelf.
- **PUT** (`/api/tasks/:id`): Replace data – like updating a book’s details entirely.
- **PATCH** (optional): Partially update – like fixing a typo in the book description.
- **DELETE** (`/api/tasks/:id`): Remove data – like removing a damaged book.

### Status Codes Simplified
- `200 OK` – Everything worked.
- `201 Created` – New resource persists.
- `400 Bad Request` – Client sent invalid data.
- `401 Unauthorized` – Missing/invalid credentials.
- `403 Forbidden` – Authenticated but not allowed.
- `404 Not Found` – Resource doesn’t exist.
- `500 Internal Server Error` – Something broke on the server.

### Headers
- `Content-Type: application/json` – Tells server the body format.
- `Authorization: Bearer <token>` – Sends JWT for protected routes.
- `Accept: application/json` – Client expects JSON response.

---

## API Testing Tutorial

### Using Postman or Insomnia
1. Create a new collection “Starter Kit API”.
2. Add requests for each endpoint.
3. Set base URL to `http://localhost:4000/api`.
4. Use Pre-request scripts to insert tokens automatically (optional advanced step).

**Register Request:**
- Method: POST
- URL: `/auth/register`
- Body (JSON):
  ```json
  {
    "email": "learner@example.com",
    "password": "Test123!",
    "name": "Curious Coder"
  }
  ```

**Login Request:**
- Method: POST
- URL: `/auth/login`
- Body: same as above.
- Response includes a `token` – copy it for protected routes.

### Testing with cURL

```bash
curl -X POST http://localhost:4000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"learner@example.com","password":"Test123!"}'
```

Use environment variables:
```bash
export API_URL=http://localhost:4000/api
export TOKEN=your.jwt.token
curl -H "Authorization: Bearer $TOKEN" "$API_URL/tasks"
```

### Understanding Request/Response JSON
- Requests must send valid JSON—watch for trailing commas or quotes.
- Responses include structured data and helpful messages.
- Errors provide `code`, `message`, and sometimes `details` arrays.

---

## Interactive API Reference

### Auth Endpoints
| Endpoint | Purpose | Request Body | Success Response | Common Errors |
|----------|---------|--------------|------------------|---------------|
| `POST /auth/register` | Create user | `email`, `password`, `name` | `201`, JWT token | `400` validation errors, `409` email exists |
| `POST /auth/login` | Authenticate user | `email`, `password` | `200`, JWT token | `401` invalid credentials |

### Task Endpoints
| Endpoint | Purpose | Requirements | Success Response | Common Errors |
|----------|---------|--------------|------------------|---------------|
| `GET /tasks` | List tasks for user | JWT | `200` with array | `401` invalid token |
| `POST /tasks` | Create task | JWT, title | `201` with task | `400` invalid body |
| `PUT /tasks/:id` | Update task | JWT, valid id | `200` updated task | `404` if task not found |
| `PATCH /tasks/:id/complete` | Mark complete | JWT | `200` updated status | `409` already completed |
| `DELETE /tasks/:id` | Remove task | JWT, ownership | `204` no content | `403` if not owner |

### Try-It-Yourself Prompts
1. **Exercise:** Add a request to filter tasks by status using query params (`GET /tasks?status=completed`).
2. **Exercise:** Handle pagination (`GET /tasks?page=2&pageSize=10`).
3. **Exercise:** Write a failing test case (send invalid JSON) and observe validation errors.

> Keep experimenting—APIs feel magical once you understand the flow. When ready, explore how the frontend consumes these endpoints in `docs/05-CONNECTING-FRONTEND-BACKEND.md`.
