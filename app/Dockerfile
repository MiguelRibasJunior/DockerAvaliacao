FROM php:8.2-apache

# Instala extensão PDO para MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilita mod_rewrite do Apache
RUN a2enmod rewrite

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o código da aplicação
COPY src/ /var/www/html/

# Permissões
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
