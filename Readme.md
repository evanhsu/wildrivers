# Wild Rivers Fire Crew

This is an old PHP app that's still kickin'.

## Running the site locally

There's no local db bundled with this project. If you want to run the app locally, connect to the hosted dev database.

Copy `.env.example` to `.env` and set real credentials. For dev against the droplet database, use the values in `.env.dev` after you have replaced `DB_PASSWORD=replace-me` (see **Droplet deploy env** below).

Then start up the app with docker-compose:

    docker-compose up

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

## Droplet deploy env

GitHub Actions reads encrypted dotenv files in the repo root (`.env.production.sops`, `.env.dev.sops`). Plaintext templates live in `.env.production` and `.env.dev` (gitignored).

1. Set a strong `DB_PASSWORD` in each file (must not stay `replace-me`, or `create-app-users.sh` skips the user).
2. Confirm `DB_HOST` and `DB_PORT` match `tofu output -raw database_host` and `database_port` in `smirksoftware-infra/tofu` if the cluster ever changes.
3. Encrypt and commit:

        sops -e --input-type dotenv --output-type dotenv .env.production > .env.production.sops
        sops -e --input-type dotenv --output-type dotenv .env.dev > .env.dev.sops

Use the same `SOPS_AGE_KEY` / age recipient as the firecrew repo (see `.sops.yaml`). The deploy workflow writes decrypted env to `/opt/stack/env/wildrivers.env` or `wildrivers-dev.env` on the droplet.

On the droplet, set `MYSQL_ATTR_SSL_CA=/certs/ca-certificate.crt` (the CA is mounted by Compose). Local docker-compose can omit that variable for non-TLS databases.
