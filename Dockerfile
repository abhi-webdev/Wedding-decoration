FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .

# Set container environment variables
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=0
ENV REAL_IP_HEADER=1

# Copy custom Nginx configuration for Laravel routing
COPY nginx-site.conf /etc/nginx/sites-available/default.conf

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Clear stale caches and link storage
RUN php artisan optimize:clear || true
RUN php artisan storage:link || true

# Set proper permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["/start.sh"]