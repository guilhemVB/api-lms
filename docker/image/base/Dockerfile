FROM php:5.6-apache

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
RUN pecl install apcu-4.0.11 && echo extension=apcu.so > /usr/local/etc/php/conf.d/apcu.ini
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

