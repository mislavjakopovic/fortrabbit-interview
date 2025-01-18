build:
	ln -sfv .docker/docker-compose.yaml docker-compose.yaml
	-cp .docker/docker-compose.override.yaml.dist docker-compose.override.yaml
	-cp .env.local.dist .env.local
	docker-compose build

test:
	rm -rf var/cache/test
	rm -rf var/log/test.log
	docker compose exec php-fortrabbit-interview bin/console cache:clear
	docker compose exec php-fortrabbit-interview bin/phpunit

fix:
	docker compose exec php-fortrabbit-interview vendor/bin/php-cs-fixer fix
