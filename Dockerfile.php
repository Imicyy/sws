FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/php

# Enable required PHP extensions for this project.
RUN docker-php-ext-install pdo pdo_mysql \
	&& sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/000-default.conf

# Enable Apache rewrite for .htaccess front-controller routing.
RUN a2enmod rewrite

# Allow .htaccess overrides in Apache default site.
RUN sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

EXPOSE 80
