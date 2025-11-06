# Use official PHP image with Apache
FROM php:8.2-apache

# Install mysqli extension and other common extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files to container
COPY . /var/www/html/

# Expose port 80
EXPOSE 80
