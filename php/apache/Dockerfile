# Utiliser la dernière image PHP avec Apache
FROM php:8.3-apache

# Configurer le ServerName pour éviter les avertissements lors du démarrage d'Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Mettre à jour les paquets et installer les dépendances nécessaires
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        locales apt-utils git unzip libicu-dev g++ libpng-dev libxml2-dev \
        libzip-dev libonig-dev libxslt-dev libpq-dev libfreetype6-dev \
        libjpeg62-turbo-dev libwebp-dev libxpm-dev zlib1g-dev libsqlite3-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configurer les locales pour en_US et fr_FR
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

# Copier php.ini-production comme base et configurer les limites d'upload
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
    echo "upload_max_filesize = 20M" >> /usr/local/etc/php/php.ini && \
    echo "post_max_size = 25M" >> /usr/local/etc/php/php.ini && \
    echo "file_uploads = On" >> /usr/local/etc/php/php.ini

# Configurer et installer les extensions PHP requises
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql gd opcache intl zip calendar dom mbstring xsl fileinfo

# Installer Composer (gestionnaire de dépendances PHP)
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Installer et configurer Xdebug pour le débogage PHP
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Configurer un répertoire pour les fichiers uploadés et définir les permissions
RUN mkdir -p /var/www/uploads && chown -R www-data:www-data /var/www/uploads

# Créer un alias pour accéder au répertoire des uploads via HTTP
RUN echo "Alias /uploads /var/www/uploads" >> /etc/apache2/sites-available/000-default.conf

# Activer les modules Apache nécessaires
RUN a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /var/www/

# Exposer le port 80 pour Apache
EXPOSE 80