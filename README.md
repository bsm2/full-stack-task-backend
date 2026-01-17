# Task Management API

A Laravel-based REST API for managing tasks with user authentication, task creation, updating, and filtering features. Tests are written using Pest.

---

## ✅ Setup Steps

1. **Clone the repository**

```bash
git clone https://github.com/your-username/full-stack-task-backend
cd full-stack-task-backend
```

2. **Install dependencies**

```bash
composer install
npm install
```

3. **Copy environment file**

```bash
cp .env.example .env
```

4. **Configure `.env`**

Set your database and other credentials:

```
APP_NAME=TaskAPI
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_api
DB_USERNAME=root
DB_PASSWORD=secret
```

5. **Generate app key**

```bash
php artisan key:generate
```

6. **Run migrations**

```bash
php artisan migrate
```

7. **Optional: Seed the database**

```bash
php artisan db:seed
```

8. **Run the development server**

```bash
php artisan serve
```

API will be available at: `http://localhost:8000`

---

## 🧪 How to Run Tests

The project uses **Pest** for testing.

1. **Run all tests**

```bash
php artisan test

```

2. **Run a specific test file**

```bash
vendor/bin/pest tests/Feature/TaskTest.php
```

3. **Test database**

Ensure `.env.testing` or `phpunit.xml` is configured for a testing database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_api_test
DB_USERNAME=root
DB_PASSWORD=secret
```

---

## 🔐 Authentication Instructions

### Register

- **Endpoint:** `POST /api/register`
- **Payload:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login

- **Endpoint:** `POST /api/login`
- **Payload:**
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```
- **Response:** Returns `token`, and `user`.

### Logout

- **Endpoint:** `POST /api/logout`
- **Headers:** `Authorization: Bearer {token}`

---

## 📦 API Endpoints Summary

### Tasks

| Method | Endpoint | Description | Payload / Query |
|--------|---------|-------------|----------------|
| GET    | `/api/tasks` | List all tasks for admin and owner tasks only for users | Optional query params: `search`, `status`, `priority`, `due_date_start`, `due_date_end` |
| GET    | `/api/tasks/{id}` | Get task details | — |
| POST   | `/api/tasks` | Create new task | `{ "title", "description", "status", "priority", "due_date" }` |
| PUT    | `/api/tasks/{id}` | Update task y owner | `{ "title", "description", "status", "priority", "due_date" }` |
| DELETE | `/api/tasks/{id}` | Delete task by owner | — |

**Filter Examples:**

- `/api/tasks?search=run&status=done&priority=low&due_date_start=2025-10-29&due_date_end=2025-10-31`
- `/api/tasks?status=pending&priority=high`

---

## ⚡ Notes

- All endpoints require **authentication** except registration and login.  
- Use **Bearer Token** in `Authorization` header for API calls.  
- Tests are isolated using `RefreshDatabase` and use **factories** for data creation.

