.PHONY: start stop update init cache composer yarn console

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

cache:
	rm -r project/var/cache

php: start
	docker-compose exec php bash

migrate: start
	docker-compose exec php bin/console d:m:m --no-interaction

composer: start
	docker-compose exec php composer $(filter-out $@,$(MAKECMDGOALS))

yarn: start
	docker-compose exec php yarn $(filter-out $@,$(MAKECMDGOALS))

console: start
	docker-compose exec php bin/console $(filter-out $@,$(MAKECMDGOALS))