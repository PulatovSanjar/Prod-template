DC ?= docker compose -f docker-compose.dev.yml
PHP ?= php-fpm
COMPOSER ?= php-fpm

build:
	$(DC) up -d --build

up:        ## start stack
	$(DC) up -d

down:      ## stop & remove containers
	$(DC) down --remove-orphans

composer-install:
	$(DC) run --rm $(COMPOSER) composer install --prefer-dist --no-interaction

composer-update:
	$(DC) run --rm $(COMPOSER) composer update --prefer-dist --no-interaction

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
