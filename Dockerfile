FROM php:8.2-cli

# System dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
 && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application first (so artisan exists)
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Cache (optional)
RUN php artisan config:cache || true \
 && php artisan route:cache || true \
 && php artisan view:cache || true

CMD php -S 0.0.0.0:${PORT:-10000} -t public
