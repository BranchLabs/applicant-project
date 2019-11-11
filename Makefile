.PHONY: up stop down build start restart

include .env
export $(shell sed 's/=.*//' .env)

up:
	docker-compose up -d

stop:
	docker-compose stop

down:
	docker-compose down

build:
	docker-compose build

database_create:
	cat database.sql | docker exec -i $${COMPOSE_PROJECT_NAME}_mariadb_1 /usr/bin/mysql -u $${MYSQL_USER} --password=$${MYSQL_PASSWORD} $${MYSQL_DATABASE}

start: up

restart: down up
