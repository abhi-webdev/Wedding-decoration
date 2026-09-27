FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .

ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

RUN composer install --no-dev --optimize-autoloader

EXPOSE 80

CMD ["/start.sh"]
