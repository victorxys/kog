# Use an official PHP image with Apache.
FROM php:7.4-apache

# Install necessary PHP extensions for WordPress.
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Enable Apache's mod_rewrite for WordPress permalinks.
RUN a2enmod rewrite

# Copy application source code to the container.
COPY . /var/www/html/

# Set correct permissions for the web directory.
RUN chown -R www-data:www-data /var/www/html
