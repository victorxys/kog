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

# Create wp-config.php dynamically using environment variables\nRUN set -eux; \\\n\twp_config_path=\'/var/www/html/wp-config.php\'; \\\n\tif [ ! -e \"\$wp_config_path\" ]; then \\\n\t\tcat > \"\$wp_config_path\" <<EOF\n<?php\n// ** MySQL settings - You can get this info from your web host ** //\n/** The name of the database for WordPress */\ndefine( \'DB_NAME\', getenv(\'WORDPRESS_DB_NAME\') );\n\n/** MySQL database username */\ndefine( \'DB_USER\', getenv(\'WORDPRESS_DB_USER\') );\n\n/** MySQL database password */\ndefine( \'DB_PASSWORD\', getenv(\'WORDPRESS_DB_PASSWORD\') );\n\n/** MySQL hostname */\ndefine( \'DB_HOST\', getenv(\'WORDPRESS_DB_HOST\') );\n\n/** Database Charset to use in creating database tables. */\ndefine( \'DB_CHARSET\', \'utf8\' );\n\n/** The Database Collate type. Don\'t change this if in doubt. */\ndefine( \'DB_COLLATE\', \'\' );\n\n/**#@+\n * Authentication Unique Keys and Salts.\n *\n * Change these to different unique phrases!\n * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}\n * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.\n *\n * @since 2.6.0\n */\ndefine(\'AUTH_KEY\',         \'put your unique phrase here\');\ndefine(\'SECURE_AUTH_KEY\',  \'put your unique phrase here\');\ndefine(\'LOGGED_IN_KEY\',    \'put your unique phrase here\');\ndefine(\'NONCE_KEY\',        \'put your unique phrase here\');\ndefine(\'AUTH_SALT\',        \'put your unique phrase here\');\ndefine(\'SECURE_AUTH_SALT\', \'put your unique phrase here\');\ndefine(\'LOGGED_IN_SALT\',   \'put your unique phrase here\');\ndefine(\'NONCE_SALT\',       \'put your unique phrase here\');\n\n/**#@-*/\n\n/**\n * WordPress Database Table prefix.\n *\n * You can have multiple installations in one database if you give each\n * a unique prefix. Only numbers, letters, and underscores please!\n */\n\$table_prefix = \'wp_\';\n\n/**\n * For developers: WordPress debugging mode.\n *\n * Change this to true to enable the display of notices during development.\n * It is strongly recommended that plugin and theme developers use WP_DEBUG\n * in their development environments.\n *\n * For information on other constants that can be used for debugging,\n * visit the Codex.\n *\n * @link https://codex.wordpress.org/Debugging_in_WordPress\n */\ndefine( \'WP_DEBUG\', false );\n\n/* That\'s all, stop editing! Happy publishing. */\n\n/** Absolute path to the WordPress directory. */\nif ( ! defined( \'ABSPATH\' ) ) {\n\tdefine( \'ABSPATH\', __DIR__ . \'/\' );\n}\n\nrequire_once ABSPATH . \'wp-settings.php\';\nEOF\n\tfi\n\n# Set correct permissions for the web directory.\nRUN chown -R www-data:www-data /var/www/html