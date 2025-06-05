#!/bin/bash

# Laravel Docker Helper Commands

case "$1" in
    "start")
        echo "🚀 Starting Laravel containers..."
        docker-compose up -d
        ;;
    "stop")
        echo "🛑 Stopping Laravel containers..."
        docker-compose down
        ;;
    "restart")
        echo "🔄 Restarting Laravel containers..."
        docker-compose down
        docker-compose up -d
        ;;
    "rebuild")
        echo "🔨 Rebuilding Laravel containers..."
        docker-compose down
        docker-compose up -d --build
        ;;
    "logs")
        echo "📋 Showing Laravel application logs..."
        docker-compose logs -f laravel.test
        ;;
    "shell")
        echo "💻 Entering Laravel container shell..."
        docker-compose exec laravel.test bash
        ;;
    "artisan")
        shift
        echo "🎨 Running artisan command: $@"
        docker-compose exec laravel.test php artisan "$@"
        ;;
    "composer")
        shift
        echo "📦 Running composer command: $@"
        docker-compose exec laravel.test composer "$@"
        ;;
    "migrate")
        echo "📊 Running database migrations..."
        docker-compose exec laravel.test php artisan migrate
        ;;
    "fresh")
        echo "🆕 Fresh migration with seed..."
        docker-compose exec laravel.test php artisan migrate:fresh --seed
        ;;
    "test")
        echo "🧪 Running tests..."
        docker-compose exec laravel.test php artisan test
        ;;
    "status")
        echo "📊 Container status..."
        docker-compose ps
        ;;
    *)
        echo "🐳 Laravel Docker Helper"
        echo ""
        echo "Available commands:"
        echo "  start      - Start containers"
        echo "  stop       - Stop containers"
        echo "  restart    - Restart containers"
        echo "  rebuild    - Rebuild and start containers"
        echo "  logs       - Show application logs"
        echo "  shell      - Enter container shell"
        echo "  artisan    - Run artisan commands"
        echo "  composer   - Run composer commands"
        echo "  migrate    - Run database migrations"
        echo "  fresh      - Fresh migration with seed"
        echo "  test       - Run tests"
        echo "  status     - Show container status"
        echo ""
        echo "Examples:"
        echo "  ./docker-helpers.sh start"
        echo "  ./docker-helpers.sh artisan make:controller UserController"
        echo "  ./docker-helpers.sh composer require laravel/sanctum"
        ;;
esac 