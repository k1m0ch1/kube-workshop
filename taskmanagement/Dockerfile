FROM php:8.2-fpm

# Install system deps + PHP extensions required by Laravel 11
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev libonig-dev libxml2-dev unzip curl \
    && docker-php-ext-install \
        pdo pdo_pgsql \
        mbstring zip xml dom bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy source code and install dependencies
COPY . .
RUN composer install --no-interaction --prefer-dist

# Generate app key and create required directories
RUN cp .env.example .env \
    && php artisan key:generate \
    && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Entrypoint runs migrations then starts PHP-FPM
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
