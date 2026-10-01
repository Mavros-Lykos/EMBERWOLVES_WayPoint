FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    postgresql-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Expose port 8000 for Artisan serve
EXPOSE 8000

# Start Artisan serve
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
