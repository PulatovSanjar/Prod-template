# minimal Makefile
DC ?= docker compose
PROJECT ?= magma-bank-service
PHP ?= php

.PHONY: up down build restart ps logs sh artisan migrate seed comp-install comp-update

up:        ## start stack
	$(DC) up -d

down:      ## stop & remove containers
	$(DC) down --remove-orphans

build:     ## build images
	$(DC) build --pull

restart:   ## restart stack
	$(DC) down --remove-orphans
	$(DC) up -d

artisan:   ## run artisan: make artisan cmd="optimize:clear"
	$(DC) exec $(PHP) php artisan $(cmd)

migrate:   ## run migrations + seed
	$(DC) exec $(PHP) php artisan migrate --seed

composer-install: ## composer install
	$(DC) run --rm $(PHP) composer install

composer-update:  ## composer update
	$(DC) run --rm $(PHP) composer update
