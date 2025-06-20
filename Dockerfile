# Imagen oficial de PHP con Apache
FROM php:8.2-apache

# Habilitar extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copiar los archivos del proyecto al contenedor
COPY . /var/www/html/

# Dar permisos
RUN chown -R www-data:www-data /var/www/html

# Puerto expuesto
EXPOSE 80
