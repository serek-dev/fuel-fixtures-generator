start: stop build check

check: cs stan unit integration

unit:
	docker-compose run --rm composer tests:unit
integration:
	docker-compose run --rm composer tests:integration

stan:
	docker-compose run --rm composer phpstan

cs:
	docker-compose run --rm composer phpcs
cs_fix:
	docker-compose run --rm composer phpcs:fix

build:
	docker-compose pull
	docker-compose build --pull
	docker-compose run --rm composer install --ignore-platform-reqs

stop:
	docker-compose down -v --remove-orphans
	docker network prune -f
