#!/bin/bash -e

SCRIPT_DIR=$(dirname "$(realpath "$0")")

source "$SCRIPT_DIR/../setup.sh"

start_init "php"

info_section "Waiting for services"
wait "mariadb"

#info_section "Installing dependencies.. $(pwd)"
#run "composer install"

#info_section "Warming up cache.."
#run "bin/console cache:warmup"

#info "Initializing Doctrine.."
#run "bin/console doctrine:migrations:migrate --no-interaction"

#info "Generating JWT keys.."
#run "bin/console lexik:jwt:generate-keypair --skip-if-exists"

#info "Applying fixtures.."
#run "bin/console doctrine:fixtures:load --group=dev --append --no-interaction"

info "Setting user rights.."
run "chown -R www-data:www-data $SETUP_APP_DIR"

end_init "php"
exec "$@"
