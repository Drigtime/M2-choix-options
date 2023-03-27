include .env

.DEFAULT_GOAL := help
.PHONY: help start stop update init bash dumpdb importdb cc console migration migrate controller entity crud jsroute composer cu cudr yarn yu yui yuil yw yd yb

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?## .*$$)|(^## )' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

start: ## Start the project
	@if [ `docker-compose ps -q php | wc -l` -eq 0 ]; then \
		docker-compose up -d; \
	else \
		echo "Project is already started"; \
	fi

stop: ## Stop the project
	@if [ `docker-compose ps -q php | wc -l` -eq 1 ]; then \
		docker-compose down; \
	else \
		echo "Project is already stopped"; \
	fi

restart: # Restart the projet
	make stop
	make start

check-dependencies: ## Check the dependencies
	docker-compose exec php sh -c "yarn check --integrity"

install: start # Install the dependencies
	@if [ ! -d "project/vendor" ] || [ ! -s "project/composer.lock" ] || [ "project/composer.json" -nt "project/vendor" ] || [ "project/composer.json" -nt "project/composer.lock" ]; then \
		docker-compose exec php composer install; \
	else \
		echo "Composer dependencies are already installed"; \
	fi
	@if [ ! -d "project/node_modules" ] || [ ! -s "project/yarn.lock" ] || [ "project/package.json" -nt "project/node_modules" ] || [ "project/package.json" -nt "project/yarn.lock" ]; then \
		docker-compose exec php yarn install; \
	else \
		echo "Yarn dependencies are already installed"; \
	fi

init: ## Init the project
	make start
	make install
	make jsroute
	make yb
	docker-compose exec php bin/console d:d:c --if-not-exists --no-interaction
# docker-compose exec php bin/console d:m:m --no-interaction
# docker-compose exec php bin/console d:s:u --force --no-interaction

bash: start ## Enter in the php container
	docker-compose exec php bash

dumpdb: start ## Dump the database
	docker-compose exec db_server mysqldump -u $(MYSQL_USER) --password=$(MYSQL_PASS) $(MYSQL_DB) > backup.sql

importdb: start ## Import the database
	docker-compose exec -T db_server mysql -u $(MYSQL_USER) --password=$(MYSQL_PASS) $(MYSQL_DB) < backup.sql

cc: start ## Clear the cache
	docker-compose exec php bin/console c:c

console: start ## Pass a command to the symfony console
	docker-compose exec php bin/console $(filter-out $@,$(MAKECMDGOALS))

migration: start ## Create a migration
	docker-compose exec php bin/console make:migration --no-interaction

migrate: start ## Migrate the database
	docker-compose exec php bin/console d:m:m --no-interaction

mm: start ## Migrate the database
	make migration
	make migrate

controller: start ## Create a controller
	docker-compose exec php bin/console make:controller
	docker-compose exec php chmod -R 777 src/Controller

entity: start ## Create an entity
	docker-compose exec php bin/console make:entity
	docker-compose exec php chmod -R 777 src/Entity src/Repository

crud: start ## Create a crud
	docker-compose exec php bin/console make:crud
	docker-compose exec php chmod -R 777 src/Controller templates

jsroute: start ## Generate the js routes
	docker-compose exec php bin/console fos:js-routing:dump

composer: start ## Pass a command to composer
	docker-compose exec php composer $(filter-out $@,$(MAKECMDGOALS))

cu: start ## Composer update
	docker-compose exec php composer update

cudr: start ## Composer update dry run
	docker-compose exec php composer update --dry-run

yarn: start ## Pass a command to yarn
	docker-compose exec php yarn $(filter-out $@,$(MAKECMDGOALS))

yu: start ## Yarn upgrade
	docker-compose exec php yarn upgrade

yui: start ## Yarn upgrade interactive
	docker-compose exec php yarn upgrade-interactive

yuil: start ## Yarn upgrade interactive latest
	docker-compose exec php yarn upgrade-interactive --latest

yw: jsroute ## Start the yarn watch
	docker-compose exec php yarn watch

yd: jsroute ## Start the yarn dev
	docker-compose exec php yarn dev

yb: jsroute ## Start the yarn build
	docker-compose exec php yarn build


