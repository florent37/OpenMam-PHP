FROM php:7.1.7-apache
RUN ls
COPY php.ini /usr/local/etc/php/

RUN curl -sL https://deb.nodesource.com/setup_6.x | bash - && apt install -y nodejs
WORKDIR /var/www

RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data
