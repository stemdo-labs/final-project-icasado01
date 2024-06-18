FROM php:8.2-apache
 
# Instalar las extensiones necesarias de PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql
 
# Copiar el contenido de la aplicación al directorio raíz de Apache
COPY ./app /var/www/html/
 
# Establecer el directorio de trabajo
WORKDIR /var/www/html/
 
# Dar permisos adecuados a los archivos
RUN chown -R www-data:www-data /var/www/html
RUN chmod 755 -R /var/www/html
 
# Exponer el puerto 80
EXPOSE 80