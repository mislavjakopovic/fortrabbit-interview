#!/bin/bash -e

SCRIPT_DIR=$(dirname "$(realpath "$0")")

source "$SCRIPT_DIR/../setup.sh"

start_init "nginx"

info_section "Waiting for services"
wait "php"

end_init "nginx"
exec "$@"
