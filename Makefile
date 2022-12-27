.PHONY: start stop update init php cache migration migrate controller entity crud composer yarn console

start:
	docker-compose up -d

stop:
	docker-compose down

update:
	docker-compose up -d --build

init: 
	docker-compose up -d --build
	docker-compose exec php composer install
	docker-compose exec php yarn install
	docker-compose exec php yarn build

php: start
	docker-compose exec php bash

cache:
	docker-compose exec php bin/console cache:clear

migration: start
	docker-compose exec php bin/console make:migration

migrate: start
	docker-compose exec php bin/console d:m:m --no-interaction

controller: start
	docker-compose exec php bin/console make:controller

entity: start
	docker-compose exec php bin/console make:entity

crud: start
	docker-compose exec php bin/console make:crud

composer: start
	docker-compose exec php composer $(filter-out $@,$(MAKECMDGOALS))

yarn: start
	docker-compose exec php yarn $(filter-out $@,$(MAKECMDGOALS))

console: start
	docker-compose exec php bin/console $(filter-out $@,$(MAKECMDGOALS))