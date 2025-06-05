# Laravel Docker Setup Guide

This Laravel project is configured to run with Docker using MySQL and Redis.

## Prerequisites

- Docker
- Docker Compose

## Quick Start

1. **Update your `.env` file** with the following Docker-specific configuration:

```bash
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=password

# Redis Configuration
REDIS_CLIENT=phpredis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Cache Configuration
CACHE_STORE=redis
SESSION_DRIVER=redis

# Docker Environment Variables (add these to your .env)
WWWUSER=1000
WWWGROUP=1000
SAIL_XDEBUG_MODE=off
SAIL_XDEBUG_CONFIG="client_host=host.docker.internal"
APP_PORT=8000
FORWARD_DB_PORT=3306
FORWARD_REDIS_PORT=6379
```

2. **Build and start the containers:**

```bash
docker-compose up -d --build
```

3. **Install dependencies (if needed):**

```bash
docker-compose exec laravel.test composer install
```

4. **Generate application key (if needed):**

```bash
docker-compose exec laravel.test php artisan key:generate
```

5. **Run database migrations:**

```bash
docker-compose exec laravel.test php artisan migrate
```

## Access Points

- **Laravel Application**: http://localhost:8000
- **MySQL Database**: localhost:3306
- **Redis**: localhost:6379

## Available Services

- `laravel.test`: Laravel application running on PHP 8.3
- `mysql`: MySQL 8.0 database
- `redis`: Redis cache server

## Useful Commands

### Enter the application container:
```bash
docker-compose exec laravel.test bash
```

### Run Artisan commands:
```bash
docker-compose exec laravel.test php artisan [command]
```

### Run Composer commands:
```bash
docker-compose exec laravel.test composer [command]
```

### View logs:
```bash
docker-compose logs laravel.test
```

### Stop containers:
```bash
docker-compose down
```

### Rebuild containers:
```bash
docker-compose down
docker-compose up -d --build
```

## Troubleshooting

1. **Permission issues**: Make sure your user ID matches the WWWUSER in .env (usually 1000)
2. **Database connection**: Ensure the MySQL container is fully started before running migrations
3. **Port conflicts**: If ports 8000, 3306, or 6379 are in use, update the port mappings in docker-compose.yml

## File Structure

```
docker/
└── 8.3/
    ├── Dockerfile          # PHP 8.3 container configuration
    ├── start-container     # Container startup script
    ├── supervisord.conf    # Process manager configuration
    └── php.ini            # PHP configuration
``` 