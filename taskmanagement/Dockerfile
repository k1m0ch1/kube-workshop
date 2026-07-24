# syntax=docker/dockerfile:1
# =============================================================================
# Task Management - Laravel Application
# =============================================================================
#
# SSH Git Pull Usage:
#   docker build --ssh default -t taskmanagement .
#
# Without SSH (local copy):
#   docker build -t taskmanagement .
#
# Run:
#   docker run -p 8080:80 taskmanagement
# =============================================================================

# =============================================================================
# Stage 1: Composer – install PHP dependencies
# =============================================================================
FROM composer:2.7 AS vendor

WORKDIR /app

# Install git + SSH client for private repo access
RUN apk add --no-cache git openssh-client

# Pre-trust GitHub's host key so SSH doesn't prompt
RUN mkdir -p /root/.ssh && \
    ssh-keyscan github.com >> /root/.ssh/known_hosts && \
    chmod 700 /root/.ssh && \
    chmod 600 /root/.ssh/known_hosts

# ── Option A: SSH git clone from a private repository ──────────────────────
# Enable this block (and remove the COPY below) when deploying from a private repo.
# Pass your SSH key at build time:  docker build --ssh default .
#
# ARG REPO_URL=git@github.com:yourusername/taskmanagement.git
# RUN --mount=type=ssh \
#     git clone "$REPO_URL" . && \
#     echo "Cloned from $REPO_URL"
# ────────────────────────────────────────────────────────────────────────────

# ── Option B: copy local source (default for local development) ─────────────
COPY . .
# ────────────────────────────────────────────────────────────────────────────

# Install PHP dependencies (production only, no scripts yet)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# =============================================================================
# Stage 2: Production image (PHP-FPM + Nginx + Supervisor)
# =============================================================================
FROM php:8.2-fpm-alpine AS production

LABEL maintainer="your-email@example.com"
LABEL description="Task Management Application - Laravel 11"

# Install system packages and PHP extensions
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        opcache \
        gd \
        bcmath \
        mbstring

WORKDIR /var/www/html

# Copy application from vendor stage (includes vendor/)
COPY --from=vendor /app .

# Copy Docker service configuration files
COPY docker/nginx.conf      /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini          /usr/local/etc/php/conf.d/app.ini

# Ensure required directories exist
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    database

# Create SQLite database file
RUN touch database/database.sqlite

# Copy .env and generate application key
RUN cp .env.example .env \
    && php artisan key:generate --no-interaction \
    && php artisan storage:link --no-interaction || true

# Run migrations against the SQLite DB
RUN php artisan migrate --force --no-interaction

# Optimize for production
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Fix permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache \
    && chmod 664 /var/www/html/database/database.sqlite

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=10s --start-period=15s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
