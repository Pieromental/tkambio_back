
FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    sqlite3 \
    libsqlite3-dev \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-configure pdo_sqlite --with-pdo-sqlite=/usr \
    && docker-php-ext-install pdo_sqlite gd zip \
    && a2enmod rewrite


RUN a2enmod rewrite headers


ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer



WORKDIR /var/www/html


COPY . /var/www/html


RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache


RUN composer install --optimize-autoloader


EXPOSE 80


CMD ["apache2-foreground"]
