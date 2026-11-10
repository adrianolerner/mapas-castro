FROM hacklab/mapasculturais:latest

COPY themes /var/www/src/themes
COPY plugins /var/www/src/plugins

WORKDIR /var/www/src
RUN pnpm install --recursive && pnpm run build
WORKDIR /var/www

COPY docker/common/config.d /var/www/config/common.d
COPY docker/production/config.d /var/www/config/config.d

RUN mkdir -p /var/www/var/sessions /var/www/var/logs /var/www/var/private-files /var/www/var/saas-files \
    && chown -R www-data:www-data /var/www/var \
    && printf "opcache.enable=1\nopcache.memory_consumption=256\nopcache.interned_strings_buffer=32\nopcache.max_accelerated_files=32531\nopcache.revalidate_freq=60\nrealpath_cache_size=16384k\nrealpath_cache_ttl=600\n" > /usr/local/etc/php/conf.d/zz-performance.ini
