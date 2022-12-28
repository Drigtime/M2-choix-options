
.DEFAULT_GOAL := help
.PHONY: start stop update init php cache migration migrate controller entity crud composer yarn console watch help

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?## .*$$)|(^## )' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

start: ## Start the project
	docker-compose up -d

stop: ## Stop the project
	docker-compose down

update: ## Update the project
	docker-compose up -d --build

init: ## Init the project
	docker-compose up -d --build
	docker-compose exec php composer install
	docker-compose exec php yarn install
	docker-compose exec php yarn build
	docker-compose exec php bin/console d:d:c --if-not-exists --no-interaction
	docker-compose exec php bin/console d:m:m --no-interaction
	docker-compose exec php bin/console d:s:u --force --no-interaction
	docker-compose exec php bin/console fos:js-routing:dump

php: start ## Enter in the php container
	docker-compose exec php bash

cache: start ## Clear the cache
	docker-compose exec php bin/console c:c

migration: start ## Create a migration
	docker-compose exec php bin/console make:migration

migrate: start ## Migrate the database
	docker-compose exec php bin/console d:m:m --no-interaction

controller: start ## Create a controller
	docker-compose exec php bin/console make:controller

entity: start ## Create an entity
	docker-compose exec php bin/console make:entity

crud: start ## Create a crud
	docker-compose exec php bin/console make:crud

jsroute: start ## Generate the js routes
	docker-compose exec php bin/console fos:js-routing:dump

composer: start ## Pass a command to composer
	docker-compose exec php composer $(filter-out $@,$(MAKECMDGOALS))

yarn: start ## Pass a command to yarn
	docker-compose exec php yarn $(filter-out $@,$(MAKECMDGOALS))

watch: start ## Start the yarn watch
	docker-compose exec php yarn watch

console: start ## Pass a command to the symfony console
	docker-compose exec php bin/console $(filter-out $@,$(MAKECMDGOALS))
