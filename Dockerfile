# Use the official PHP 8.1 CLI image as the base image
FROM php:8.1-cli

# Install necessary system dependencies
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files separately (to optimize caching)
COPY composer.json composer.lock* ./

# Install PHP dependencies at build time
RUN composer install --no-dev --optimize-autoloader || true

# Copy the rest of the application files
COPY . .

# Ensure proper permissions
RUN chown -R www-data:www-data /var/www

# Ensure public directory exists
RUN mkdir -p /var/www/public

# Expose port for Lumen
EXPOSE 8000

# Start the Lumen application
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
