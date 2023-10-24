#!/usr/bin/env bash

mysql --user=root --password="$MYSQL_ROOT_PASSWORD" <<-EOSQL
    CREATE DATABASE IF NOT EXISTS consumer_api;
    GRANT ALL PRIVILEGES ON \`consumer_api%\`.* TO '$MYSQL_USER'@'%';
EOSQL
