FROM lms-base

ARG XDEBUG
ARG BLACKFIRE_SERVER_ID
ARG BLACKFIRE_SERVER_TOKEN

RUN mkdir -p /var/www/front/web

RUN a2enmod headers

# Apache vhost
COPY apache/vhost.conf /etc/apache2/sites-enabled/000-default.conf

RUN if $XDEBUG; \
    then pecl install xdebug; \
    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini; \
    echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini; \
    echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini; \
    echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini; \
    fi

