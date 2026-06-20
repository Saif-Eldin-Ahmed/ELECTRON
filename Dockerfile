# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions required for the application
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mysqli

# Enable Apache mod_rewrite for routing and friendly URLs (if needed)
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory to Apache's document root
WORKDIR /var/www/html

# Copy composer files and install dependencies
COPY composer.json ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy the rest of the application files to the container
COPY . .

# Adjust Apache configuration to listen on the dynamic port provided by Railway ($PORT)
# Railway injects the PORT environment variable at runtime
ENV PORT=80
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g' /etc/apache2/sites-available/000-default.conf

# Set proper ownership and permissions for web server files
RUN chown -R www-data:www-data /var/www/html

# Expose the dynamic port
EXPOSE ${PORT}

# Run Apache in the foreground
CMD ["apache2-foreground"]
