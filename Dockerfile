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

# --- AGGRESSIVE APACHE CONFIGURATION ---
# Remove all default site configurations
RUN rm -f /etc/apache2/sites-enabled/* /etc/apache2/sites-available/*

# Copy our custom Apache configuration and enable it
COPY apache.conf /etc/apache2/sites-available/kog.conf
RUN a2ensite kog.conf
# --- END AGGRESSIVE APACHE CONFIGURATION ---

# Enable Apache's mod_rewrite for WordPress permalinks.
RUN a2enmod rewrite

# Copy application source code to the container.
COPY . /var/www/html/

COPY wp-config-docker.php /var/www/html/wp-config.php