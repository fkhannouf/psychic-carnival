# Deployment Guide

This guide covers different deployment scenarios for the Laravel Todo application.

## Table of Contents

1. [Local Development](#local-development)
2. [Docker Deployment](#docker-deployment)
3. [Production Deployment](#production-deployment)
4. [GitHub Actions CI/CD](#github-actions-cicd)
5. [MCP Server Integration](#mcp-server-integration)

## Local Development

### Prerequisites
- PHP 8.3+
- Composer
- Node.js and NPM
- SQLite

### Setup Steps

```bash
# Clone the repository
git clone https://github.com/fkhannouf/psychic-carnival.git
cd psychic-carnival

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
touch database/database.sqlite
php artisan migrate

# Build assets (optional, using CDN by default)
npm run build

# Start development server
php artisan serve
```

Access the application at `http://localhost:8000`

## Docker Deployment

### Using Docker Compose (Recommended)

```bash
# Build and start
docker-compose up -d

# View logs
docker-compose logs -f

# Stop
docker-compose down
```

Access at `http://localhost:8000`

### Using Pre-built Image from GitHub Container Registry

```bash
# Pull the latest image
docker pull ghcr.io/fkhannouf/psychic-carnival:latest

# Run the container
docker run -d \
  --name laravel-todo \
  -p 8000:80 \
  -v $(pwd)/database:/var/www/html/database \
  ghcr.io/fkhannouf/psychic-carnival:latest

# View logs
docker logs -f laravel-todo

# Stop and remove
docker stop laravel-todo
docker rm laravel-todo
```

### Building Custom Docker Image

```bash
# Build the image
docker build -t my-todo-app .

# Run the container
docker run -d -p 8000:80 my-todo-app
```

## Production Deployment

### Environment Variables

Create a `.env` file with production settings:

```env
APP_NAME="Todo App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=sqlite
# For MySQL/PostgreSQL:
# DB_CONNECTION=mysql
# DB_HOST=your-db-host
# DB_PORT=3306
# DB_DATABASE=your_database
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# MCP Integration (optional)
MCP_ENABLED=true
MCP_SERVER_URL=http://mcp-server:3000
```

### Docker Compose for Production

```yaml
services:
  app:
    image: ghcr.io/fkhannouf/psychic-carnival:latest
    container_name: laravel-todo-app
    restart: always
    ports:
      - "80:80"
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - DB_CONNECTION=sqlite
    volumes:
      - ./database:/var/www/html/database
      - ./storage:/var/www/html/storage
    networks:
      - todo-network

networks:
  todo-network:
    driver: bridge
```

### Using a Reverse Proxy (Nginx)

Example Nginx configuration:

```nginx
server {
    listen 80;
    server_name yourdomain.com;

    location / {
        proxy_pass http://localhost:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

For HTTPS with Let's Encrypt:

```bash
# Install certbot
sudo apt-get install certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --nginx -d yourdomain.com

# Certificate will auto-renew
```

## GitHub Actions CI/CD

The repository includes a complete CI/CD pipeline that:

### On Every Push:
1. Runs automated tests
2. Builds Docker image
3. Pushes to GitHub Container Registry

### Workflow File
Located at `.github/workflows/ci-cd.yml`

### Image Tags
Images are tagged with:
- `latest` - Latest version from main branch
- Branch name - e.g., `copilot-create-todo-app-in-laravel`
- Commit SHA - e.g., `main-abc1234`

### Using CI/CD Images

```bash
# Pull specific version
docker pull ghcr.io/fkhannouf/psychic-carnival:main

# Pull by commit SHA
docker pull ghcr.io/fkhannouf/psychic-carnival:main-abc1234

# Pull latest
docker pull ghcr.io/fkhannouf/psychic-carnival:latest
```

## MCP Server Integration

### Basic MCP Setup

1. **Start the Laravel application**:
```bash
docker-compose up -d
```

2. **Configure MCP Server** (see `mcp-server.json`):
```json
{
  "server": {
    "host": "localhost",
    "port": 3000,
    "protocol": "http"
  }
}
```

3. **Update docker-compose.yml** to include MCP server:
```yaml
services:
  app:
    # ... existing config ...
    environment:
      - MCP_ENABLED=true
      - MCP_SERVER_URL=http://mcp-server:3000

  mcp-server:
    image: mcp/server:latest
    container_name: mcp-server
    ports:
      - "3000:3000"
    environment:
      - TARGET_SERVICE=http://app:80
    volumes:
      - ./mcp-server.json:/config/mcp-server.json
    depends_on:
      - app
```

### Testing MCP Integration

```bash
# List todos
curl http://localhost:8000/api/todos

# Create todo via MCP
curl -X POST http://localhost:8000/api/todos \
  -H "Content-Type: application/json" \
  -d '{"title": "MCP Todo", "description": "Created via MCP"}'

# Toggle todo
curl -X POST http://localhost:8000/api/todos/1/toggle \
  -H "Accept: application/json"
```

For detailed MCP information, see [MCP_INTEGRATION.md](MCP_INTEGRATION.md)

## Health Checks

The application includes a health check endpoint:

```bash
curl http://localhost:8000/up
```

## Monitoring

### View Application Logs

```bash
# Docker logs
docker logs -f laravel-todo-app

# Laravel logs
tail -f storage/logs/laravel.log
```

### Database Backups

For SQLite:
```bash
# Backup
cp database/database.sqlite database/backup-$(date +%Y%m%d).sqlite

# Restore
cp database/backup-20250101.sqlite database/database.sqlite
```

## Troubleshooting

### Common Issues

1. **Permission Errors**:
```bash
docker exec -it laravel-todo-app chmod -R 775 storage bootstrap/cache
docker exec -it laravel-todo-app chown -R www-data:www-data storage bootstrap/cache
```

2. **Database Not Found**:
```bash
docker exec -it laravel-todo-app php artisan migrate --force
```

3. **Container Won't Start**:
```bash
# Check logs
docker logs laravel-todo-app

# Rebuild image
docker-compose build --no-cache
docker-compose up -d
```

## Scaling

For horizontal scaling, consider:
1. Using MySQL/PostgreSQL instead of SQLite
2. Implementing Redis for cache and sessions
3. Load balancing with multiple containers
4. Using managed container services (AWS ECS, Azure Container Instances, etc.)

## Support

For issues or questions:
- Open an issue on GitHub
- Check the [README.md](README.md) for more information
- Review [MCP_INTEGRATION.md](MCP_INTEGRATION.md) for MCP-specific details
