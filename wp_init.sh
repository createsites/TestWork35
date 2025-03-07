#!/bin/bash

su -c "wp core install \
    --url='${WP_URL}:${APP_PORT}' \
    --title='${WP_TITLE}' \
    --admin_user=${WP_ADMIN_USER} \
    --admin_password=${WP_ADMIN_PASSWORD} \
    --admin_email=${WP_ADMIN_EMAIL} \
    --skip-themes \
    --skip-plugins" wpuser

# устанавливаем site url и home
su -c " \
  wp option update siteurl ${WP_URL}:${APP_PORT} \
  && wp option update home ${WP_URL}:${APP_PORT} \
" wpuser

# устанавливаем права
chmod -R g+w wp-content
chown -R www-data:www-data wp-content/themes/

# активируем нашу тему
su -c "wp theme activate test_work_35" wpuser

# удаляем лишние темы
su -c "wp theme delete twentytwentyfive twentytwentyfour twentytwentythree" wpuser

# удаляем ненужные посты и страницы
su -c 'wp post delete $(wp post list --post_type=post,page --format=ids)' wpuser

# создаем страницу с кастомным шаблоном
su -c 'wp post create --post_type=page --post_title="Cities Weather" --post_status=publish --porcelain \
  | xargs -I {} wp post meta update {} _wp_page_template "page-cities.php"' wpuser

# устанавливаем ее как домашнюю страницу, для вывода таблицы городов на главную
su -c 'wp post list --post_type="page" --format=ids | xargs -I {} wp option update page_on_front {} && wp option update show_on_front page' wpuser

# создаем страны
su -c 'wp term create countries "GB"' wpuser
su -c 'wp term create countries "Germany"' wpuser
su -c 'wp term create countries "France"' wpuser

# добавляем несколько городов для примера
su -c 'wp post create --post_type=cities --post_title="London" --post_status=publish --meta_input="{\"_latitude\":\"51.509865\", \"_longitude\":\"-0.118092\", \"_custom_id\":\"2643743\"}" --porcelain \
  | xargs -I {} wp post term add {} countries gb' wpuser

su -c 'wp post create --post_type=cities --post_title="Paris" --post_status=publish --meta_input="{\"_latitude\":\"48.85341\", \"_longitude\":\"2.3488\", \"_custom_id\":\"2988507\"}" --porcelain \
  | xargs -I {} wp post term add {} countries france' wpuser

su -c 'wp post create --post_type=cities --post_title="Berlin" --post_status=publish --meta_input="{\"_latitude\":\"52.520008\", \"_longitude\":\"13.404954\", \"_custom_id\":\"6545310\"}" --porcelain \
  | xargs -I {} wp post term add {} countries germany' wpuser

