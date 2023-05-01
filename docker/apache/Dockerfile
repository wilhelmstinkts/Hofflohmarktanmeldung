FROM php:8.1-apache

RUN apt-get -y update
RUN apt-get -y install git
RUN curl -L -C - --progress-bar -o /usr/local/bin/composer https://getcomposer.org/composer.phar
RUN chmod 755 /usr/local/bin/composer
RUN apt-get -y install zlib1g-dev libicu-dev g++
RUN docker-php-ext-install pdo_mysql mysqli intl
# RUN pecl install xdebug-2.9.6 && docker-php-ext-enable xdebug
RUN echo "date.timezone=UTC" >> /usr/local/etc/php/conf.d/timezone.ini
RUN a2enmod rewrite
RUN service apache2 restart