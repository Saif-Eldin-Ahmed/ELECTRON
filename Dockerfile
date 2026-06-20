FROM php:8.2-apache

# Install system dependencies + PHP extensions commonly needed for MySQL e-commerce apps
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite (useful for clean URLs / .htaccess rules)
RUN a2enmod rewrite

# Fix "More than one MPM loaded" error:
# mod_php requires the prefork MPM; disable event/worker explicitly and ensure prefork is enabled
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true \
    && a2enmod mpm_prefork

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set Apache's document root to /var/www/html (default), copy project files there
WORKDIR /var/www/html
COPY . /var/www/html/

# Install PHP dependencies via Composer (no dev dependencies, optimized autoloader)
RUN composer install --no-dev --optimize-autoloader --no-interaction || true

# Ensure correct ownership/permissions for Apache to read/write where needed
RUN chown -R www-data:www-data /var/www/html

# Apache listens on port 80 by default; Railway maps its $PORT to this internally via its proxy,
# but Apache itself should listen on the port Railway expects.
ENV APACHE_PORT=8080
RUN sed -i "s/80/${APACHE_PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

EXPOSE 8080

CMD ["apache2-foreground"]