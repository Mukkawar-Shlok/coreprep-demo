#!/bin/bash

# Laravel Docker Setup Script

echo "🐳 Setting up Laravel with Docker..."

# Make start-container executable
chmod +x docker/8.3/start-container

# Update .env file for Docker configuration
echo "📝 Updating .env file for Docker..."

# Backup original .env
cp .env .env.backup

# Update database configuration
sed -i.bak 's/DB_HOST=.*/DB_HOST=mysql/' .env
sed -i.bak 's/DB_USERNAME=.*/DB_USERNAME=root/' .env
sed -i.bak 's/DB_PASSWORD=.*/DB_PASSWORD=password/' .env

# Update Redis configuration
sed -i.bak 's/REDIS_HOST=.*/REDIS_HOST=redis/' .env

# Update cache configuration
sed -i.bak 's/CACHE_STORE=.*/CACHE_STORE=redis/' .env
sed -i.bak 's/SESSION_DRIVER=.*/SESSION_DRIVER=redis/' .env

# Add Docker-specific environment variables if they don't exist
if ! grep -q "WWWUSER" .env; then
    echo "" >> .env
    echo "# Docker Environment Variables" >> .env
    echo "WWWUSER=1000" >> .env
    echo "WWWGROUP=1000" >> .env
    echo "SAIL_XDEBUG_MODE=off" >> .env
    echo "SAIL_XDEBUG_CONFIG=\"client_host=host.docker.internal\"" >> .env
    echo "APP_PORT=8000" >> .env
    echo "FORWARD_DB_PORT=3306" >> .env
    echo "FORWARD_REDIS_PORT=6379" >> .env
fi

# Clean up backup files
rm -f .env.bak

echo "🔧 Building and starting Docker containers..."
docker-compose up -d --build

echo "⏳ Waiting for containers to be ready..."
sleep 10

echo "📦 Installing dependencies..."
docker-compose exec laravel.test composer install --no-interaction

echo "🔑 Generating application key..."
docker-compose exec laravel.test php artisan key:generate --no-interaction

echo "📊 Running database migrations..."
docker-compose exec laravel.test php artisan migrate --no-interaction

echo "✅ Setup complete!"
echo ""
echo "🌐 Your Laravel application is now running at: http://localhost:8000"
echo "🗄️  MySQL is available at: localhost:3306"
echo "⚡ Redis is available at: localhost:6379"
echo ""
echo "📋 Useful commands:"
echo "  docker-compose logs laravel.test    # View application logs"
echo "  docker-compose exec laravel.test bash  # Enter container"
echo "  docker-compose down                 # Stop containers" 