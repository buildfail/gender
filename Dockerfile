FROM php:7.1

RUN apt-get update -q && apt-get install -y git

RUN pecl install gender-1.1.0 && docker-php-ext-enable gender

WORKDIR /gender

COPY . /gender

RUN curl --silent --show-error https://getcomposer.org/installer | php

RUN ./composer.phar install

RUN ./composer.phar test

