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

# устанавливаем права
chmod -R g+w wp-content
chown -R www-data:www-data wp-content/themes/

# удаляем лишние темы
su -c "wp theme delete twentytwentyfive twentytwentyfour twentytwentythree" wpuser

# активируем нашу тему
su -c "wp theme activate test_work_35" wpuser

# эти шаги не нужны, т.к. темы берутся из смонтированного volume
# устанавливаем тему storefront
# su -c "wp theme install --activate https://downloads.wordpress.org/theme/storefront.4.6.1.zip" wpuser
# создаем дочернюю тему
# su -c "wp scaffold child-theme --parent_theme=storefront --activate test_work_35" wpuser

