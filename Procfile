
php bin/console cache:clear --env=prod && \
php bin/console cache:warmup --env=prod && \
mkdir -p var/sessions var/log && \
chmod -R 777 var/cache var/sessions var/log && \
php -S 0.0.0.0:$PORT -t public