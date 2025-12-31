FROM php:8.2-cli

# System dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
 && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Ensure Laravel writable directories exist
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache || true

# Start (Free plan: no shell, so run needed commands here)
CMD php artisan migrate --force || true \
 && php artisan config:clear || true \
 && php artisan route:clear || true \
 && php -S 0.0.0.0:${PORT:-10000} -t public
