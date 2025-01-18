build:
	ln -sfv .docker/docker-compose.yaml docker-compose.yaml
	-cp .docker/docker-compose.override.yaml.dist docker-compose.override.yaml
	-cp .env.local.dist .env.local
	docker compose build

test:
	-rm -rf var/cache/test
	-rm -rf var/log/test.log
	-docker compose exec php-fortrabbit-interview bin/console cache:clear
	-docker compose exec php-fortrabbit-interview bin/phpunit

lint:
	-docker compose exec php-fortrabbit-interview vendor/bin/php-cs-fixer fix
	-docker compose exec php-fortrabbit-interview bin/console lint:container
	-docker compose exec php-fortrabbit-interview bin/console lint:twig templates/
	-docker compose exec php-fortrabbit-interview bin/console lint:yaml config/ --parse-tags

cache:
	-docker compose exec php-fortrabbit-interview rm -rf var/cache/*
	-docker compose exec php-fortrabbit-interview bin/console cache:clear

fixtures:
	-docker compose exec php-fortrabbit-interview bin/console doctrine:fixtures:load --group=dev --no-interaction

audit:
	-docker compose exec php-fortrabbit-interview composer audit

check: cache audit lint test
