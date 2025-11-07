# Home Budget App

A clean and modular **Symfony 7.3** application built with a modern Dockerized stack — featuring a secure JWT authentication system, PostgreSQL database, REST API with OpenAPI/Redoc documentation, and full PHPUnit test coverage.

---

## Tech Stack

| Component | Description |
|------------|-------------|
| **Symfony 7.3** | Backend framework (PHP 8.3) |
| **PostgreSQL** | Primary database |
| **Nginx** | Web server and reverse proxy |
| **PHP-FPM 8.3** | Runtime for the Symfony application |
| **LexikJWTAuthenticationBundle** | JWT-based authentication |
| **Redocly / OpenAPI** | API documentation (`redocly/redoc` container) |
| **PHPUnit** | Unit & functional web tests |
| **PHPStan** | Static analysis for code quality |
| **Docker Compose** | Container orchestration and environment setup |

---

## Quick Start

### 1. Clone the repository

```bash
git clone https://github.com/yourusername/home-budget-app.git
cd home-budget-app
```

---

### 2. Build and start the containers

```bash
make app.up
```

This command will:
- Build all Docker containers
- Start the full stack (`nginx`, `php`, `postgres`, `redoc`)
- Automatically run database migrations to prepare the schema

Once complete:
- The app will be accessible at **http://localhost:5100**
- API documentation (Redoc) will be accessible at **http://localhost:2100**

---

### 3. Run tests

To load fixtures and run the PHPUnit test suite:

```bash
make app.tests
```

This will:
- Load demo fixtures into the database
- Execute the full web test suite using `bin/phpunit`

---

### 4. Connect to the PHP container

If you need to run Symfony or Composer commands manually:

```bash
make app.connect
```

This will open an interactive shell inside the PHP container.

---

## Common Make Commands

| Command | Description |
|----------|-------------|
| `make app.build` | Build Docker images |
| `make app.up` | Start all containers (with build & migrations) |
| `make app.down` | Stop and remove containers |
| `make app.restart` | Restart the full stack |
| `make app.connect` | Open a shell inside the PHP container |
| `make app.logs` | Follow container logs |
| `make app.run_migrations` | Run Doctrine migrations |
| `make app.run_fixtures` | Load database fixtures |
| `make app.tests` | Load fixtures & run PHPUnit tests |

---

## Authentication

Authentication is handled via **JWT tokens** generated using the [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle).

- Login: `POST /api/login`  
  Returns a JWT token for authorized requests.

Protected endpoints require this header:

```
Authorization: Bearer <your-token>
```

---

## API Documentation

API endpoints are documented using **OpenAPI 3.1** and served through **Redocly**.

Once containers are up, visit:

**[http://localhost:2100](http://localhost:2100)**

You’ll find a categorized, interactive documentation view (Authentication, Budget, Expenses, Expense Categories).

---

## Quality & Testing

- **Static analysis**: PHPStan ensures code safety and cleanliness.
- **Feature & web tests**: PHPUnit simulates real HTTP requests to test endpoints and business logic.
- **Fixtures**: Doctrine fixtures provide repeatable test data.

---

## Useful Notes

- Database migrations and fixtures are idempotent — feel free to rebuild anytime using:
  ```bash
  make app.run_migrations
  make app.run_fixtures
  ```
- To rebuild everything from scratch:
  ```bash
  make app.down
  make app.up
  ```

---

## About

This project was built as a **technical demonstration** of clean backend architecture and DevOps practices:
- Proper Dockerized Symfony setup
- CI-friendly Makefile automation
- JWT-secured REST API with pagination, sorting, filtering, and validation
- Full OpenAPI documentation and testing discipline

---

**Enjoy exploring the code**
> If you have Docker and Make installed, you can get the entire stack running in under 2 minutes.