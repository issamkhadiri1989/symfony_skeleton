install: build up composer update load

remove:
	docker container rm sqli_web_server sqli_database_server sqli_phpmyadmin sqli_smtp_server

enter:
	docker-compose exec server bash

update:
	docker-compose exec server composer update symfony/*

composer:
	docker-compose exec server composer self-update
	docker-compose exec server composer install

build:
	docker-compose build

up:
	docker-compose up -d

start:
	docker-compose up -d --no-recreate --remove-orphans

recreate:
	docker-compose up -d --force-recreate

boot:
	docker-compose start

stop:
	docker-compose stop

shutdown:
	docker container stop $$(docker container ps -aq)

cc: cache-no-warmup doctrine

cache-no-warmup:
	docker-compose exec server php bin/console c:c --no-warmup

doctrine:
	docker-compose exec server php bin/console doctrine:cache:clear-metadata
	docker-compose exec server php bin/console doctrine:cache:clear-query
	docker-compose exec server php bin/console doctrine:cache:clear-result

ps:
	docker-compose ps

database: create-db schema load

schema:
	docker-compose exec server php bin/console doctrine:schema:update --force

create-db:
	docker-compose exec server php bin/console doctrine:database:create

load:
	docker-compose exec server php bin/console doctrine:fixtures:load

drop-database:
	docker-compose exec server php bin/console doctrine:database:drop --force