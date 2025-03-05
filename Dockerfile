FROM wordpress:6.7.2

RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp

RUN groupadd wpgroup \
    && useradd -m -G wpgroup,www-data -s /bin/bash wpuser

COPY wp_init.sh .
RUN chmod +x wp_init.sh
