FROM php:8.3-apache
RUN docker-php-ext-install mysqli && a2enmod rewrite
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/uploads \
 && printf "upload_max_filesize=6M\npost_max_size=8M\n" > /usr/local/etc/php/conf.d/uploads.ini
EXPOSE 80
