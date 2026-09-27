FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

# Copy all project files
COPY . .

# Set container environment variables
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=0
ENV REAL_IP_HEADER=1

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Fix directory permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["/start.sh"]