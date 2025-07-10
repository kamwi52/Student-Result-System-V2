# =================================================================
# Dockerfile for Laravel on Render.com
# =================================================================

# Part 1: Base Image
# Use an official, lightweight PHP image. '8.2-fpm-alpine' is a good choice.
# 'fpm' is a process manager for PHP, and 'alpine' is a small Linux distribution.
FROM php:8.2-fpm-alpine

# Part 2: Install System Dependencies
# We need to install tools that Laravel and PHP extensions depend on.
# This includes things for image processing (libpng), zip files, and Node.js/npm for our frontend.
RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Part 3: Install PHP Extensions
# This command installs common PHP extensions that Laravel uses.
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    gd \
    zip \
    bcmath

# Part 4: Install Composer (PHP's package manager)
# This securely copies the Composer executable from its official image.
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Part 5: Prepare the Application Directory
# Set the working directory inside the server environment to '/app'.
WORKDIR /app

# Part 6: Copy Your Project Files
# This copies all the files from your local project into the '/app' directory on the server.
COPY . .

# Part 7: Install Dependencies
# First, install the PHP packages from your composer.json file.
# The flags are for a fast, optimized production installation.
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist --optimize-autoloader

# Second, install the JavaScript packages and build your CSS/JS for production.
RUN npm install && npm run build

# Part 8: Set Permissions
# The 'storage' and 'bootstrap/cache' directories need to be writable by the web server.
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

# Part 9: Run Database Migrations
# This command automatically runs your migrations every time you deploy.
RUN php artisan migrate --force

# Part 10: Expose the Port
# This tells the environment that your application will be listening on port 8000.
EXPOSE 8000

# Part 11: The Start Command
# This is the final command that is run to start your Laravel application server.
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]