FROM php:8.2-cli

WORKDIR /var/www/html

# install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy project files
COPY . .

# install dependencies
RUN composer install --no-dev --optimize-autoloader

# expose port Render itatumia
EXPOSE 10000

# start Laravel server
CMD php artisan serve --host=0.0.0.0 --port=10000