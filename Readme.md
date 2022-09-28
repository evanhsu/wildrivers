# Wild Rivers Fire Crew

This is an old PHP app that's still kickin'.

## Docker build

To run this app on your local machine:

    docker build -t wildrivers .
    docker run -p 80:8080 \
        --env DB_USERNAME=$MY_DB_USERNAME \
        --env DB_PASSWORD=$MY_DB_PASSWORD \
        --env DB_DATABASE=$MY_DB_DATABASE \
        --env DB_HOST=db.smirksoftware.com \
        --env DB_PORT=3306 \
        --env APP_URL="http://wildrivers.localhost" \
        -v "$(pwd)"/admin/requisition_images:/var/www/html/admin/requisition_images \
        -v "$(pwd)"/env-vars.conf:/etc/php8/php-fpm.d/env-vars.conf \
        wildrivers:latest

