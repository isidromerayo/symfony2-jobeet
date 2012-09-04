#!/bin/bash

# prepare database
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
# schema
php app/console doctrine:schema:update --force
# load data
php app/console doctrine:fixtures:load
# create user to admin
php app/console hcuv:jobeet:users admin admin
