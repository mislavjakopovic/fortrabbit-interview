#!/bin/bash -e

SCRIPT_DIR=$(dirname "$(realpath "$0")")

SETUP_PROJECT_DIR=$(realpath "$SCRIPT_DIR/../../..")
SETUP_APP_DIR=$(realpath "$PWD")
SETUP_BIN_DIR=$(realpath "$SETUP_PROJECT_DIR/.docker/bin")
SETUP_WAITERS_DIR=$(realpath "$SETUP_BIN_DIR/waiter")

function start_init() {
  echo "========== Running $1 entrypoint script =========="
  cd "$SETUP_APP_DIR"
  echo "[INFO] Current working directory $PWD"
}

function end_init() {
  cd "$SETUP_APP_DIR"
  echo "[INFO] Current working directory $PWD"
  echo "========== Finished $1 entrypoint script =========="
}

function run() {
  echo "[EXEC] $1" && eval "$1"
}

function wait() {
  echo "[WAITING] $1" && eval "$SETUP_WAITERS_DIR/$1.sh"
}

function info() {
  echo "[INFO] $1"
}

function info_section() {
  echo "========== $1 =========="
}
