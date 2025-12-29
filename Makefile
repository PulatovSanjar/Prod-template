DC ?= docker compose -f compose.dev.yaml
PHP ?= php-fpm
WORKSPACE ?= workspace

up:        ## start stack
	$(DC) up -d

down:      ## stop & remove containers
	$(DC) down --remove-orphans

composer-install:
	$(DC) run --rm $(WORKSPACE) composer install
composer-update:
	$(DC) run --rm $(PHP) composer update

restart:   ## restart stack
	$(DC) down --remove-orphans
	$(DC) up -d

laravel:   ## run artisan: make laravel name="optimize:clear"
	$(DC) exec $(PHP) php artisan $(name)

cs-fix:
	$(DC) run --rm $(PHP) ./vendor/bin/php-cs-fixer fix

analyze:
	$(DC) run --rm $(PHP) ./vendor/bin/phpstan analyse --memory-limit=-1

fix:
	$(DC) run --rm $(PHP) ./vendor/bin/php-cs-fixer fix
	$(DC) run --rm $(PHP) ./vendor/bin/phpstan analyse --memory-limit=-1

refresh:
	$(DC) exec $(PHP) php artisan migrate:fresh --seed

clear: ## clear all laravel caches
	$(DC) exec $(PHP) php artisan cache:clear
	$(DC) exec $(PHP) php artisan config:clear
	$(DC) exec $(PHP) php artisan route:clear
	$(DC) exec $(PHP) php artisan view:clear

init:      ## init project: .env, composer install, npm install
	@test -f .env || cp .env.example .env
	$(MAKE) composer-install
	npm install
