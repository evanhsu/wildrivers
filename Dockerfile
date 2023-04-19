FROM trafex/php-nginx:3.0.0

USER root

# COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./php.ini /etc/php8/conf.d/settings.ini

WORKDIR /var/www/html

COPY . /var/www/html

RUN chown -R nobody:nobody .

EXPOSE 8080

USER nobody