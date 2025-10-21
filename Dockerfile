# Use an official PHP image with Apache, locking to the amd64 platform.
FROM --platform=linux/amd64 php:7.4-apache

# Install necessary PHP extensions, htpasswd utility, and other tools.
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    apache2-utils \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli gd \
    && docker-php-ext-enable mysqli

# Create the htpasswd file for basic authentication inside the image.
RUN htpasswd -c -b /etc/apache2/.htpasswd allin allin

# Disable the default Apache site (if any) and enable our custom one
# The default site is usually 000-default.conf, which we are overwriting.
# Explicitly disabling and then enabling ensures our config is the only one active.
RUN a2dissite 000-default.conf || true # Use || true to prevent build failure if it's already disabled or not found
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

# Enable Apache's mod_rewrite for WordPress permalinks.
RUN a2enmod rewrite

# Copy application source code to the container.
COPY . /var/www/html/

# Set correct permissions for the web directory.
RUN chown -R www-data:www-data /var/www/html