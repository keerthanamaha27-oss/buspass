# Use the official PHP image with Apache
FROM php:8.2-apache

# Copy all files from your project into the Apache server folder
COPY . /var/www/html/

# Expose port 80 to the web
EXPOSE 80
