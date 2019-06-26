UID=$(shell id -u)
GID=$(shell id -g)
FILE=docker-compose.yml
CONTAINER=php

bash: ## enter inside php bash
	docker-compose -f ${FILE} exec --user=${UID} ${CONTAINER} sh

build: ## build dockers
	docker-compose -f ${FILE} build

up: ## up -d dockers
	docker-compose -f ${FILE} up -d

down: ## down dockers
	docker-compose -f ${FILE} down

install: ## make composer install
	docker-compose -f ${FILE} exec --user=${UID} ${CONTAINER} sh -c "php bin/composer.phar install"

help: ## Display this help message
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'