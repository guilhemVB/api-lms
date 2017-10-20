FROM php:7.0-apache

ARG XDEBUG

# Packages installation
RUN apt-get update && apt-get install -y \
    git \
    vim \
    wget \
    mysql-client \
    php-pear \
    zlib1g-dev \
    libxml2-dev

RUN docker-php-ext-install zip soap

# Apache2 configuration
#RUN pecl install apcu-4.0.11 && echo extension=apcu.so > /usr/local/etc/php/conf.d/apcu.ini
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
COPY php/php.ini /usr/local/etc/php/

# SSH configuration
COPY ssh /root/.ssh

# Composer configuration
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN composer global require "hirak/prestissimo:^0.3"

RUN ln -sf /dev/stdout /var/log/apache2/lms-access.log \
&& ln -sf /dev/stderr /var/log/apache2/lms-error.log

COPY fix_perms /usr/bin/fix_perms
RUN chmod +x /usr/bin/fix_perms

CMD /usr/bin/fix_perms && apache2-foreground



RUN mkdir -p /var/www/api/web

# Apache vhost
COPY apache/vhost.conf /etc/apache2/sites-enabled/000-default.conf

RUN if $XDEBUG; \
    then pecl install xdebug; \
    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini; \
    echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini; \
    echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini; \
    echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini; \
    fi

