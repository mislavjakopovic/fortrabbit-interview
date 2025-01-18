#!/bin/bash -e

HOST=php-fortrabbit-interview
PORT=9000
TIMEOUT=180

wait-for-it --host=$HOST --port=$PORT --timeout=$TIMEOUT
