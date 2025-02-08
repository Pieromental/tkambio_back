# Usa PHP con Apache
FROM php:8.1-apache

# Instala dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libsqlite3-dev \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-configure pdo_sqlite --with-pdo-sqlite=/usr \
    && docker-php-ext-install pdo_sqlite gd zip \
    && a2enmod rewrite

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar permisos para evitar errores de Git
RUN git config --global --add safe.directory /var/www/html

# Cambia `DocumentRoot` en Apache para que sirva Laravel desde `public/`
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Habilita mod_rewrite para que Laravel maneje rutas amigables
RUN a2enmod rewrite

# Copia los archivos del proyecto
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Dar permisos a Laravel
RUN chmod -R 775 storage bootstrap/cache

# Generar clave de aplicación
RUN php artisan key:generate

# Exponer puerto
EXPOSE 80

# Comando de inicio
CMD ["apache2-foreground"]
