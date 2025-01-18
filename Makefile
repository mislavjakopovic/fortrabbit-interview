build:
	ln -sfv .docker/docker-compose.yaml docker-compose.yaml
	-cp .docker/docker-compose.override.yaml.dist docker-compose.override.yaml
	-cp .env.local.dist .env.local
	docker-compose build
