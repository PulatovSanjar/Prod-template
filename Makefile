DC ?= docker compose
PHP ?= php
COMPOSER ?= composer

up:        ## start stack
	$(DC) up -d

down:      ## stop & remove containers
	$(DC) down --remove-orphans

composer-install:
	$(DC) run --rm $(COMPOSER) composer install

composer-update:
	$(DC) run --rm $(COMPOSER) composer update

restart:   ## restart stack
	$(DC) down --remove-orphans
	$(DC) up -d

php-artisan:   ## run artisan: make artisan cmd="optimize:clear"
	$(DC) exec $(PHP) php artisan $(cmd)

cs-fix:
	$(DC) run --rm $(COMPOSER) ./vendor/bin/php-cs-fixer fix

analyze:
	$(DC) run --rm $(COMPOSER) ./vendor/bin/phpstan analyse --memory-limit=-1

fix:
	$(DC) run --rm $(COMPOSER) ./vendor/bin/php-cs-fixer fix
	$(DC) run --rm $(COMPOSER) ./vendor/bin/phpstan analyse --memory-limit=-1
