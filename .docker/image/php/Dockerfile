FROM php:8.3-fpm-bullseye

# Set package manager mode to run without any user prompts
ARG DEBIAN_FRONTEND=noninteractive

# Get latest packages
RUN apt-get update

# Install system packages
ARG apt_get_install="apt-get install -y --no-install-recommends"
RUN $apt_get_install apt-utils
RUN $apt_get_install curl
RUN $apt_get_install wget
RUN $apt_get_install git
RUN $apt_get_install vim
RUN $apt_get_install nano
RUN $apt_get_install zip
RUN $apt_get_install unzip
RUN $apt_get_install ca-certificates
RUN $apt_get_install wait-for-it
RUN $apt_get_install cron
RUN $apt_get_install g++
RUN $apt_get_install libicu-dev

# Install PHP extensions
ARG php_ext_install="docker-php-ext-install"
RUN $php_ext_install pdo_mysql
RUN $php_ext_install intl
RUN $php_ext_install opcache

# Configure for service
ADD config "$PHP_INI_DIR/conf.d"
RUN ln -s "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN usermod -u 1000 www-data

# Install Composer: https://getcomposer.org/download/
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
RUN chmod 755 /usr/local/bin/composer

# Configure miscellaneous settings
RUN ln -snf /usr/share/zoneinfo/Europe/Prague /etc/localtime
RUN echo 'alias ll="ls -alk"' >> ~/.bashrc
RUN git config --global --add safe.directory /var/www/fortrabbit-interview/backend

# Expose ports to Docker network
EXPOSE 9000
