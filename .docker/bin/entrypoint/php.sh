#!/bin/bash -e

SCRIPT_DIR=$(dirname "$(realpath "$0")")

source "$SCRIPT_DIR/../setup.sh"

start_init "php"

info_section "Waiting for services"
wait "mariadb"

info_section "Installing dependencies.. $(pwd)"
run "composer install"

info_section "Warming up cache.."
run "bin/console cache:warmup"

info "Applying Doctrine migrations for dev environment.."
run "bin/console doctrine:migrations:migrate --no-interaction"

info "Applying fixtures.."
run "bin/console doctrine:fixtures:load --group=dev --no-interaction"

info "Creating database for test environment.."
run "bin/console doctrine:database:create --env=test --if-not-exists"

info "Applying Doctrine migrations for test environment.."
run "bin/console doctrine:migrations:migrate --env=test --no-interaction"

info "Setting user rights.."
run "chown -R www-data:www-data $SETUP_APP_DIR"

info "Checking installed libraries for security vulnerabilities.."
run "composer audit"

end_init "php"
exec "$@"
