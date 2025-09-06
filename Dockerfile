# =================================================================
# Dockerfile for Laravel on Railway (Corrected Version)
# =================================================================

# Part 1: Base Image (No change)
FROM php:8.2-fpm-alpine

# Part 2: Install System Dependencies
# CHANGED: Added 'netcat-openbsd' which is required for our wait script.
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    netcat-openbsd

# Part 3: Install PHP Extensions (No change)
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    gd \
    zip \
    bcmath

# Part 4: Install Composer (No change)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Part 5: Prepare the Application Directory (No change)
WORKDIR /app

# Part 6: Copy Your Project Files (No change)
COPY . .

# ADDED: Copy the wait script into the image and make it executable.
COPY wait-for-db.sh /usr/local/bin/wait-for-db.sh
RUN chmod +x /usr/local/bin/wait-for-db.sh

# Part 7: Install Dependencies (No change)
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist --optimize-autoloader
RUN npm install && npm run build

# Part 8: Set Permissions (No change)
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

# Part 9: Run Database Migrations
# REMOVED: The problematic build-time migration command is now gone from here.
# RUN php artisan migrate --force

# Part 10: Expose the Port (No change)
EXPOSE 8000

# Part 11: The Start Command
# CHANGED: We now use an 'ENTRYPOINT' to run our wait script first.
# The 'CMD' is now passed TO the wait script after the database is ready.
# This CMD now correctly includes both the migration and the server start command.
ENTRYPOINT ["wait-for-db.sh"]
CMD ["sh", "-c", "php artisan optimize:clear && php artisan config:clear && php artisan migrate --force && php artisan serve --host 0.0.0.0 --port $PORT"]