FROM php:8.2-fpm

# System deps
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libpng-dev libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# supercronic for scheduler
ENV SUPERCRONIC_VERSION=0.2.29
RUN curl -fsSL "https://github.com/aptible/supercronic/releases/download/v${SUPERCRONIC_VERSION}/supercronic-linux-amd64" \
    -o /usr/local/bin/supercronic && chmod +x /usr/local/bin/supercronic

WORKDIR /var/www/html

# Install PHP deps (production only at build time)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Copy application source
COPY . .

# Build frontend assets
COPY --from=node:20-alpine /usr/local/bin/node /usr/local/bin/node
COPY --from=node:20-alpine /usr/local/bin/npm /usr/local/bin/npm
COPY --from=node:20-alpine /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN npm ci --ignore-scripts && npm run build && rm -rf node_modules

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
