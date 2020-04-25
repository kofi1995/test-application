FROM php:7.4-apache-buster

COPY src/ /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

ENV PATH="~/.composer/vendor/bin:./vendor/bin:${PATH}"