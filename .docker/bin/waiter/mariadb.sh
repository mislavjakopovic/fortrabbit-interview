#!/bin/bash -e

HOST=mariadb-fortrabbit-interview
PORT=3306
TIMEOUT=30

wait-for-it --host=$HOST --port=$PORT --timeout=$TIMEOUT
