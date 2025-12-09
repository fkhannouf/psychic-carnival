# Laravel Todo Application

A modern, full-featured Todo application built with Laravel 12, featuring a beautiful UI with Tailwind CSS, RESTful API endpoints, Docker support, and CI/CD pipeline with GitHub Actions.

## Features

- ✅ Create, read, update, and delete todos
- ✅ Mark todos as complete/incomplete
- ✅ Beautiful, responsive UI with Tailwind CSS
- ✅ RESTful API for integration with MCP (Model Context Protocol)
- ✅ Docker support with multi-stage builds
- ✅ CI/CD pipeline with GitHub Actions
- ✅ SQLite database (easily configurable to MySQL/PostgreSQL)
- ✅ Automated testing
- ✅ Build and push to GitHub Container Registry

## Requirements

- PHP 8.3 or higher
- Composer
- Node.js and NPM
- Docker (for containerized deployment)
- SQLite (or MySQL/PostgreSQL)

## Installation

### Local Development

1. Clone the repository:
```bash
git clone https://github.com/fkhannouf/psychic-carnival.git
cd psychic-carnival
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node dependencies:
```bash
npm install
```

4. Copy environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Create SQLite database:
```bash
touch database/database.sqlite
```

7. Run migrations:
```bash
php artisan migrate
```

8. Build assets:
```bash
npm run build
```

9. Start development server:
```bash
php artisan serve
```

Visit `http://localhost:8000` to see the application.

## Docker Deployment

### Using Docker Compose

1. Build and start the container:
```bash
docker-compose up -d
```

2. Access the application at `http://localhost:8000`

### Using Pre-built Image from GitHub Container Registry

```bash
docker pull ghcr.io/fkhannouf/psychic-carnival:latest
docker run -d -p 8000:80 ghcr.io/fkhannouf/psychic-carnival:latest
```

### Building Docker Image Manually

```bash
docker build -t laravel-todo-app .
docker run -d -p 8000:80 laravel-todo-app
```

## API Endpoints

The application provides RESTful API endpoints for MCP integration:

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/todos` | List all todos |
| POST | `/api/todos` | Create a new todo |
| GET | `/api/todos/{id}` | Get a specific todo |
| PUT | `/api/todos/{id}` | Update a todo |
| DELETE | `/api/todos/{id}` | Delete a todo |
| POST | `/api/todos/{id}/toggle` | Toggle todo completion status |

### Example API Usage

```bash
# List all todos
curl http://localhost:8000/api/todos

# Create a new todo
curl -X POST http://localhost:8000/api/todos \
  -H "Content-Type: application/json" \
  -d '{"title": "My Todo", "description": "Todo description"}'

# Toggle todo status
curl -X POST http://localhost:8000/api/todos/1/toggle \
  -H "Accept: application/json"
```

## MCP (Model Context Protocol) Integration

For detailed information about MCP integration, see [MCP_INTEGRATION.md](MCP_INTEGRATION.md).

The application is designed to work seamlessly with MCP servers, allowing AI models to interact with the todo application through standardized protocols.

## CI/CD Pipeline

The project includes a comprehensive GitHub Actions workflow that:

1. **Tests**: Runs automated tests on every push
2. **Builds**: Creates optimized Docker images
3. **Pushes**: Publishes images to GitHub Container Registry
4. **Tags**: Automatically tags images with:
   - Branch name
   - Commit SHA
   - `latest` tag for main branch

### Workflow Triggers

- Push to `main` branch
- Push to any `copilot/**` branch
- Pull requests to `main` branch

### Container Registry

Images are pushed to: `ghcr.io/fkhannouf/psychic-carnival`

## Testing

Run the test suite:

```bash
php artisan test
```

Run with coverage:

```bash
php artisan test --coverage
```

## Project Structure

```
.
├── app/
│   ├── Http/Controllers/
│   │   └── TodoController.php
│   └── Models/
│       └── Todo.php
├── database/
│   └── migrations/
│       └── 2025_12_09_104921_create_todos_table.php
├── docker/
│   ├── nginx.conf
│   ├── default.conf
│   └── supervisord.conf
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       └── todos/
│           ├── index.blade.php
│           ├── create.blade.php
│           └── edit.blade.php
├── routes/
│   ├── web.php
│   └── api.php
├── .github/
│   └── workflows/
│       └── ci-cd.yml
├── Dockerfile
├── docker-compose.yml
├── .dockerignore
└── MCP_INTEGRATION.md
```

## Technologies Used

- **Backend**: Laravel 12
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: SQLite (configurable)
- **Web Server**: Nginx + PHP-FPM
- **Containerization**: Docker
- **CI/CD**: GitHub Actions
- **Container Registry**: GitHub Container Registry (ghcr.io)

## Environment Variables

Key environment variables:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite

# MCP Integration (optional)
MCP_ENABLED=false
MCP_SERVER_URL=http://localhost:3000
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-source and available under the MIT License.

## Support

For issues, questions, or contributions, please open an issue on GitHub.

## Acknowledgments

- Built with [Laravel](https://laravel.com)
- Styled with [Tailwind CSS](https://tailwindcss.com)
- Deployed with [Docker](https://docker.com)
- CI/CD with [GitHub Actions](https://github.com/features/actions)
