.PHONY: help

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-25s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

# ============================================
# DEVELOPMENT COMMANDS
# ============================================

dev-build: ## Build development containers
	docker compose -f compose.dev.yaml build --no-cache

dev-up: ## Start development environment
	docker compose -f compose.dev.yaml up -d

dev-down: ## Stop development environment
	docker compose -f compose.dev.yaml down

dev-logs: ## Show development logs
	docker compose -f compose.dev.yaml logs -f

dev-ps: ## Show development container status
	docker compose -f compose.dev.yaml ps

dev-composer-install: ## Install Composer dependencies in dev
	docker compose -f compose.dev.yaml exec php-fpm composer install

dev-npm-install: ## Install npm dependencies in dev
	docker run --rm -v $(PWD):/app -w /app node:22-alpine npm install

dev-npm-build: ## Build Vite assets in dev
	docker run --rm -v $(PWD):/app -w /app node:22-alpine npm run build

dev-artisan: ## Run artisan command (usage: make dev-artisan cmd="migrate")
	docker compose -f compose.dev.yaml exec php-fpm php artisan $(cmd)

dev-test: ## Run PHPUnit tests
	docker compose -f compose.dev.yaml exec php-fpm vendor/bin/phpunit

dev-phpstan: ## Run PHPStan analysis
	docker compose -f compose.dev.yaml exec php-fpm vendor/bin/phpstan analyse

dev-shell: ## Open shell in PHP container
	docker compose -f compose.dev.yaml exec php-fpm sh

dev-mysql: ## Open MySQL client
	docker compose -f compose.dev.yaml exec mysql mysql -u laravel -psecret laravel

# ---- MIGRATIONS (DEV) ----

dev-migrate: ## Run migrations in development
	docker compose -f compose.dev.yaml exec php-fpm php artisan migrate

dev-migrate-fresh: ## Drop all tables and re-run migrations with seed (DEV)
	docker compose -f compose.dev.yaml exec php-fpm php artisan migrate:fresh --seed

dev-migrate-status: ## Show migration status (DEV)
	docker compose -f compose.dev.yaml exec php-fpm php artisan migrate:status

# ---- FULL DEV RESET ----

dev-fresh: ## Fresh dev install (down, build, up, deps, key, migrate)
	make dev-down
	make dev-build
	make dev-up
	sleep 5
	make dev-composer-install
	make dev-npm-install
	make dev-npm-build
	docker compose -f compose.dev.yaml exec php-fpm php artisan key:generate
	make dev-migrate-fresh

# ============================================
# PRODUCTION COMMANDS
# ============================================

prod-build: ## Build production images locally
	docker build -f docker/common/php-fpm/Dockerfile --target production -t laravel-php:local .
	docker build -f docker/production/nginx/Dockerfile -t laravel-nginx:local .

prod-up: ## Start production environment
	docker compose -f compose.prod.yaml up -d

prod-down: ## Stop production environment
	docker compose -f compose.prod.yaml down

prod-logs: ## Show production logs
	docker compose -f compose.prod.yaml logs -f

prod-ps: ## Show production container status
	docker compose -f compose.prod.yaml ps

# ---- MIGRATIONS (PROD) ----

prod-migrate: ## Run migrations in production (FORCE)
	docker compose -f compose.prod.yaml exec php-fpm php artisan migrate --force

prod-migrate-status: ## Show migration status (PROD)
	docker compose -f compose.prod.yaml exec php-fpm php artisan migrate:status

prod-shell: ## Open shell in production PHP container
	docker compose -f compose.prod.yaml exec php-fpm sh

prod-pull: ## Pull production images from registry
	docker compose -f compose.prod.yaml pull

# ============================================
# UTILITY COMMANDS
# ============================================

clean: ## Clean up containers and volumes
	docker compose -f compose.dev.yaml down -v
	docker compose -f compose.prod.yaml down -v

clean-all: clean ## Clean everything including images
	docker system prune -af --volumes
