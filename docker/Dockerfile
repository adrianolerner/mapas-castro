FROM hacklab/mapasculturais:latest

COPY themes /var/www/src/themes
COPY plugins /var/www/src/plugins

WORKDIR /var/www/src
RUN pnpm install --recursive && pnpm run build
WORKDIR /var/www

COPY docker/common/config.d /var/www/config/common.d
COPY docker/production/config.d /var/www/config/config.d

RUN mkdir -p /var/www/var/sessions /var/www/var/logs /var/www/var/private-files /var/www/var/saas-files \
    && chown -R www-data:www-data /var/www/var
