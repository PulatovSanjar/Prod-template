.PHONY: help

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-20s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

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
	docker run --rm -v $(PWD):/app -w /app node:22-alpine npm run builddev-artisan: ## Run artisan command (usage: make dev-artisan cmd="migrate")
	docker compose -f compose.dev.yaml exec php-fpm php artisan $(cmd)dev-test: ## Run PHPUnit tests
	docker compose -f compose.dev.yaml exec php-fpm vendor/bin/phpunitdev-phpstan: ## Run PHPStan analysis
	docker compose -f compose.dev.yaml exec php-fpm vendor/bin/phpstan analysedev-shell: ## Open shell in PHP container
	docker compose -f compose.dev.yaml exec php-fpm shdev-mysql: ## Open MySQL client
	docker compose -f compose.dev.yaml exec mysql mysql -u laravel -psecret laraveldev-fresh: ## Fresh install (down, build, up, install deps, generate key)
	make dev-down
	make dev-build
	make dev-up
	sleep 5
	make dev-composer-install
	make dev-npm-install
	make dev-npm-build
	docker compose -f compose.dev.yaml exec php-fpm php artisan key:generate
	docker compose -f compose.dev.yaml exec php-fpm php artisan migrate --seed============================================

#PRODUCTION COMMANDS
#============================================
prod-build: ## Build production images locally
	docker build -f docker/common/php-fpm/Dockerfile --target production -t laravel-php:local .
	docker build -f docker/production/nginx/Dockerfile -t laravel-nginx:local .prod-up: ## Start production environment (requires pre-built images)
	docker compose -f compose.prod.yaml up -dprod-down: ## Stop production environment
	docker compose -f compose.prod.yaml downprod-logs: ## Show production logs
	docker compose -f compose.prod.yaml logs -fprod-ps: ## Show production container status
	docker compose -f compose.prod.yaml psprod-migrate: ## Run migrations in production
	docker compose -f compose.prod.yaml exec php-fpm php artisan migrate --forceprod-shell: ## Open shell in production PHP container
	docker compose -f compose.prod.yaml exec php-fpm shprod-pull: ## Pull production images from registry
	docker compose -f compose.prod.yaml pull

#============================================
#UTILITY COMMANDS
#============================================
clean: ## Clean up containers, volumes, and images
	docker compose -f compose.dev.yaml down -v
	docker compose -f compose.prod.yaml down -vclean-all: clean ## Clean everything including images
	docker system prune -af --volumes
