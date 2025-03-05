#!/bin/bash

wp core install \
    --url='${WP_URL}:${APP_PORT}' \
    --title="${WP_TITLE}" \
    --admin_user=${WP_ADMIN_USER} \
    --admin_password=${WP_ADMIN_PASSWORD} \
    --admin_email=${WP_ADMIN_EMAIL} \
    --skip-themes \
    --skip-plugins \
    --allow-root

wp option update --allow-root siteurl ${WP_URL}:${APP_PORT}

#docker-compose exec -T wp-cli wp scaffold _s \
#  sample-theme \
#  --theme_name="Sample Theme" \
#  --author="John Doe"