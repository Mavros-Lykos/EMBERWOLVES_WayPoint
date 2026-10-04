FROM php:8.4-cli-alpine

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

# Copy all application files into the container
COPY . .

# Install PHP dependencies (using production mode)
RUN composer install --optimize-autoloader --no-dev

# Install Node dependencies and build frontend assets
RUN npm install && npm run build

# Expose port 8000 for Artisan serve
EXPOSE 8000

# Start Artisan serve (Uses PORT env var provided by Railway, defaults to 8000)
CMD sh -c "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"
