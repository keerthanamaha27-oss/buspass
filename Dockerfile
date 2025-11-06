# Use official PHP image with Apache
FROM php:8.2-apache

# Install mysqli and pdo_mysql extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite module (optional, if you use .htaccess)
RUN a2enmod rewrite

# Copy your project files to the Apache root directory
COPY . /var/www/html/

# Set proper permissions (optional)
RUN chown -R www-data:www-data /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
