FROM trafex/php-nginx:3.0.0

USER root

# The base image sets no client_max_body_size (nginx default 1m). Override the
# bundled server block so multipart uploads (requisitions, attachments) can reach PHP.
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./php.ini /etc/php8/conf.d/settings.ini
COPY ./env-vars.conf /etc/php8/php-fpm.d/env-vars.conf

WORKDIR /var/www/html

COPY . /var/www/html

RUN chown -R nobody:nobody .

EXPOSE 8080

USER nobody