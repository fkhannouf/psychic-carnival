# MCP (Model Context Protocol) Integration

This document describes how to integrate this Laravel Todo application with a Model Context Protocol server.

## What is MCP?

Model Context Protocol (MCP) is a standardized protocol that allows AI models to interact with external systems, databases, and services in a secure and controlled manner.

## MCP Server Configuration

### Basic Setup

Create an `mcp-server.json` file in your project root:

```json
{
  "name": "Laravel Todo MCP Server",
  "version": "1.0.0",
  "description": "MCP server for Laravel Todo application",
  "server": {
    "host": "localhost",
    "port": 3000,
    "protocol": "http"
  },
  "capabilities": [
    "todo.list",
    "todo.create",
    "todo.update",
    "todo.delete",
    "todo.toggle"
  ],
  "endpoints": {
    "list_todos": {
      "method": "GET",
      "path": "/api/todos",
      "description": "Retrieve all todos"
    },
    "create_todo": {
      "method": "POST",
      "path": "/api/todos",
      "description": "Create a new todo"
    },
    "update_todo": {
      "method": "PUT",
      "path": "/api/todos/{id}",
      "description": "Update an existing todo"
    },
    "delete_todo": {
      "method": "DELETE",
      "path": "/api/todos/{id}",
      "description": "Delete a todo"
    },
    "toggle_todo": {
      "method": "POST",
      "path": "/api/todos/{id}/toggle",
      "description": "Toggle todo completion status"
    }
  }
}
```

### Docker Integration with MCP

To connect the MCP server with the Docker container:

1. **Update docker-compose.yml** to include MCP server:

```yaml
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: laravel-todo-app
    ports:
      - "8000:80"
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - DB_CONNECTION=sqlite
      - MCP_ENABLED=true
      - MCP_SERVER_URL=http://mcp-server:3000
    volumes:
      - ./database:/var/www/html/database
    networks:
      - todo-network

  mcp-server:
    image: mcp/server:latest
    container_name: mcp-server
    ports:
      - "3000:3000"
    environment:
      - MCP_CONFIG=/config/mcp-server.json
      - TARGET_SERVICE=http://app:80
    volumes:
      - ./mcp-server.json:/config/mcp-server.json
    networks:
      - todo-network
    depends_on:
      - app

networks:
  todo-network:
    driver: bridge
```

2. **Start the services**:

```bash
docker-compose up -d
```

## API Endpoints for MCP Integration

### Authentication

Add API token authentication to your Laravel app (if needed):

```bash
php artisan make:middleware ApiTokenMiddleware
```

### Available API Routes

Add the following routes to `routes/api.php` for MCP access:

```php
Route::prefix('todos')->group(function () {
    Route::get('/', [TodoController::class, 'index']);
    Route::post('/', [TodoController::class, 'store']);
    Route::get('/{todo}', [TodoController::class, 'show']);
    Route::put('/{todo}', [TodoController::class, 'update']);
    Route::delete('/{todo}', [TodoController::class, 'destroy']);
    Route::post('/{todo}/toggle', [TodoController::class, 'toggle']);
});
```

## GitHub Actions Integration

The CI/CD pipeline automatically builds and pushes Docker images that can be used with MCP:

```yaml
# .github/workflows/ci-cd.yml includes:
- Build Docker image
- Push to GitHub Container Registry (ghcr.io)
- Tag with branch name, SHA, and 'latest'
```

### Using the Docker Image

Pull and run the built image:

```bash
# Pull from GitHub Container Registry
docker pull ghcr.io/fkhannouf/psychic-carnival:latest

# Run with MCP configuration
docker run -d \
  -p 8000:80 \
  -e MCP_ENABLED=true \
  -e MCP_SERVER_URL=http://your-mcp-server:3000 \
  -v $(pwd)/database:/var/www/html/database \
  ghcr.io/fkhannouf/psychic-carnival:latest
```

## MCP Security Considerations

1. **Authentication**: Implement API token authentication for MCP endpoints
2. **Rate Limiting**: Apply rate limits to prevent abuse
3. **CORS**: Configure CORS headers appropriately
4. **Validation**: Validate all incoming data from MCP requests
5. **Logging**: Log all MCP interactions for audit purposes

## Testing MCP Integration

Test the MCP endpoints:

```bash
# List all todos
curl http://localhost:3000/api/todos

# Create a new todo
curl -X POST http://localhost:3000/api/todos \
  -H "Content-Type: application/json" \
  -d '{"title": "Test Todo", "description": "Created via MCP"}'

# Toggle todo status
curl -X POST http://localhost:3000/api/todos/1/toggle
```

## Monitoring and Logging

Monitor MCP interactions through Laravel logs:

```bash
tail -f storage/logs/laravel.log
```

## Additional Resources

- [Model Context Protocol Documentation](https://modelcontextprotocol.io)
- [Laravel API Resources](https://laravel.com/docs/eloquent-resources)
- [Docker Compose Networking](https://docs.docker.com/compose/networking/)
