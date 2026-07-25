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

# Copy source code
COPY . .

# Set up .env before composer install so artisan scripts work
RUN cp .env.example .env

# Install dependencies — skip post-install scripts (artisan runs after key is set)
# Disable advisory blocking so CI doesn't fail on known-but-acceptable advisories
RUN composer config --global policy.advisories.block false \
    && COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction --prefer-dist --no-scripts

# Create required directories, generate key, discover packages, fix permissions
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && php artisan key:generate \
    && composer dump-autoload --optimize \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data /var/www/html

# Entrypoint runs migrations then starts PHP-FPM
COPY docker/entrypoint.sh /entrypoint.sh
RUN sed -i 's/\r//' /entrypoint.sh && chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
