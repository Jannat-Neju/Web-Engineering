FROM php:8.2-apache

# Install PDO and PostgreSQL extensions
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Enable Apache rewrite
RUN a2enmod rewrite

# Copy project files to container
COPY . /var/www/html/
