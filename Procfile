release: php bin/console cache:clear && bin/console cache:warmup --env=prod
web: vendor/bin/heroku-php-nginx -C heroku/nginx.conf public/