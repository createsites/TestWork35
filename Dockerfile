FROM wordpress:6.7.2-php8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    less \
    mariadb-client \
    su-exec \
    openssl \
    curl \
    git \
    unzip \
    jq \
    rsync \
    procps

RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

WORKDIR /var/www/html

COPY .env .

EXPOSE 80

CMD ["bash", "-c", "wp config create --dbname=${WP_DB_NAME} --dbuser=${WP_DB_USER} --dbpass=${WP_DB_PASSWORD} --dbhost=${WP_DB_HOST} --path=/var/www/html && wp core install --url=${WP_URL} --title='${WP_TITLE}' --admin_user=${WP_ADMIN_USER} --admin_password=${WP_ADMIN_PASSWORD} --admin_email=${WP_ADMIN_EMAIL} --path=/var/www/html && php-fpm"]
