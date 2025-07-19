FROM php:8.2-apache

# Install system dependencies, including libzip-dev (required for zip extension)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip unzip git curl \
    && docker-php-ext-install \
    pdo_mysql mbstring exif pcntl bcmath gd zip  # ✅ includes zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Change Apache DocumentRoot to Laravel's public directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies via Composer
RUN composer install --no-dev --optimize-autoloader

# Fix file/folder permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
