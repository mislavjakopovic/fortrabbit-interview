## Requirements
- GNU Make 4
- Docker 21+

### Application Stack
- PHP 8.3
- NGINX 1.18
- MariaDB 10.11
- Symfony 7.2
- Doctrine 3.3
- PHPUnit 11.5


## Installation
This project explicitly uses Docker to build and run the application.

Below are all the steps needed to start it locally on your host machine: 
1. `make build` - Build image, note that this takes some time (around 2 minutes) as it needs to compile some PHP extensions
2. `docker compose up` - Start the containers, once you see `=== Finished nginx entrypoint script ===` application should be ready.

Entrypoint script (`.docker/bin/entrypoint/php.sh`) should automatically initialize Composer, Doctrine, fixtures, test suite and others.

### Ports range
In order to avoid conflicts with other projects and application developers might have set up on
their machines, this project will bind by default to the following port range:
- `25000-25999`

Check `docker-compose.override.yaml` after build to adjust these on your own convenience.

## About the project
### Fixtures
To apply the initial dataset you can run the `make fixtures` command.
However please note that this not needed as it is already done automatically while starting up Docker container.

After running the command, database should be seeded with several hardcoded entities and half a dozen generated fully randomly.

### API Documentation
You can check the API documentation by visiting `http://localhost:25080/api/doc` on your host machine.

### Tests
The test suite uses the [DAMA Doctrine Test Bundle](https://github.com/dmaicher/doctrine-test-bundle) for managing database transactions during tests.
With the help of a PHPUnit extension class it will begin a transaction before every testcase 
and **roll it** back again after the test finished for all configured DBAL connections.

This results in a performance boost as there is no need to rebuild the schema, import a backup SQL dump or re-insert fixtures before every testcase.

You can run the tests with `make test` once all containers are built and running successfully.

### Code style
To check if code is properly formatted you can run `make lint`.
