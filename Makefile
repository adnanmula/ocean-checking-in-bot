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

init: ## init environment
	docker-compose -f ${FILE} run --rm -u ${UID}:${GID} php php bin/console ocib:environment:init

checkin: ## new checkin
	docker-compose -f ${FILE} run --rm -u ${UID}:${GID} php php bin/console ocib:checkin:add

get-checkins: ## get check ins for a given date
	docker-compose -f ${FILE} run --rm -u ${UID}:${GID} php php bin/console ocib:checkin:get $(date)

add-not-working-days: ## adds dates to not working days
	docker-compose -f ${FILE} run --rm -u ${UID}:${GID} php php bin/console ocib:not-working-days:add $(dates)

load-not-working-days: ## load weekends of given year to not working days db
	docker-compose -f ${FILE} run --rm -u ${UID}:${GID} php php bin/console ocib:not-working-days:load $(year)

stan: ## pass phpstan
	docker-compose -f ${FILE} exec --user=${UID} php sh -c "php -d memory_limit=256M vendor/bin/phpstan analyse -c phpstan.neon"

help: ## Display this help message
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'