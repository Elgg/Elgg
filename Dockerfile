# FROM php:8.1-apache

# # Installer les dépendances système nécessaires
# RUN apt-get update && apt-get install -y \
#     git \
#     unzip \
#     curl \
#     libzip-dev \
#     libonig-dev \
#     libxml2-dev \
#     libldap2-dev \
#     libpng-dev \
#     libjpeg-dev \
#     libfreetype6-dev \
#     libicu-dev \
#     libcurl4-openssl-dev \
#     libssl-dev \
#     libxslt-dev \
#     && rm -rf /var/lib/apt/lists/*

# # Configurer et installer les extensions PHP
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
#     docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu && \
#     docker-php-ext-install -j$(nproc) \
#         mysqli \
#         pdo_mysql \
#         xml \
#         mbstring \
#         curl \
#         zip \
#         intl \
#         gd \
#         soap \
#         bcmath \
#         opcache \
#         ldap \
#         xsl

# # Activer les modules Apache nécessaires
# RUN a2enmod rewrite

# # Copier la configuration Apache personnalisée
# COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# # Copier le code source Elgg dans le dossier racine web d’Apache
# COPY . /var/www/html/elgg

# RUN mkdir -p /var/elgg_data && chown -R www-data:www-data /var/elgg_data && chmod 775 /var/elgg_data

# #fichier de configuration

# #RUN mkdir -p /var/www/html/elgg/elgg-config && chown -R www-data:www-data /var/www/html/elgg/elgg-config && chmod 755 /var/www/html/elgg/elgg-config

# # Copier les fichiers de config initiaux
# #COPY elgg-config/* /var/www/html/elgg/elgg-config/
# # RUN mkdir -p /var/www/html/elgg/elgg-config && chown -R www-data:www-data /var/www/html/elgg/elgg-config && chmod 755 /var/www/html/elgg/elgg-config

# # Copier les fichiers de config initiaux
# # COPY elgg-config/* /var/www/html/elgg/elgg-config/

# # Définir le dossier de travail
# WORKDIR /var/www/html/elgg

# # Installer Composer (version recommandée)
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# # Installer les dépendances PHP avec Composer, ignore les scripts si besoin
# RUN composer install --no-dev --no-scripts --no-progress --optimize-autoloader || true

# # Donner les bons droits à Apache
# RUN chown -R www-data:www-data /var/www/html/elgg

# # Exposer le port HTTP d’Apache
# # EXPOSE 80

# RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
#     sed -i 's/:80/:8080/' /etc/apache2/sites-enabled/*.conf

# EXPOSE 80

# # Démarrer Apache en mode foreground
# CMD ["apache2-foreground"]
########################################Test#############################

# FROM php:8.1-apache

# # Install system dependencies
# RUN apt-get update && apt-get install -y \
#     git unzip curl libzip-dev libonig-dev libxml2-dev \
#     libldap2-dev libpng-dev libjpeg-dev libfreetype6-dev \
#     libicu-dev libcurl4-openssl-dev libssl-dev libxslt-dev \
#     && rm -rf /var/lib/apt/lists/*

# # Configure PHP extensions
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
#     docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu && \
#     docker-php-ext-install -j$(nproc) \
#         mysqli pdo_mysql xml mbstring curl zip intl gd \
#         soap bcmath opcache ldap xsl

# # Configure Apache
# RUN a2enmod rewrite && \
#     echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
#     sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
#     sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:8080>/' /etc/apache2/sites-enabled/000-default.conf

# # Copy application files
# COPY . /var/www/html/elgg
# COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# # Set up data directory
# RUN mkdir -p /var/elgg_data && \
#     chown -R www-data:www-data /var/elgg_data /var/www/html/elgg && \
#     chmod -R 775 /var/elgg_data

# # Install Composer
# RUN curl -sS https://getcomposer.org/installer | php -- \
#     --install-dir=/usr/local/bin --filename=composer

# # Install dependencies
# RUN composer install --no-dev --no-scripts --no-progress --optimize-autoloader || true

# # Configure PHP error reporting
# RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/errors.ini && \
#     echo "display_errors = On" >> /usr/local/etc/php/conf.d/errors.ini && \
#     echo "log_errors = On" >> /usr/local/etc/php/conf.d/errors.ini

# # Create log directory
# RUN mkdir -p /var/log/apache2 && \
#     chown www-data:www-data /var/log/apache2 && \
#     chmod 755 /var/log/apache2

# # Set working directory
# WORKDIR /var/www/html/elgg

# EXPOSE 8080
# CMD ["apache2-foreground"]

##########################################Test##############################################

FROM php:8.1-apache

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libldap2-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    libxslt-dev \
    && rm -rf /var/lib/apt/lists/*

# Configurer et installer les extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu && \
    docker-php-ext-install -j$(nproc) \
        mysqli \
        pdo_mysql \
        xml \
        mbstring \
        curl \
        zip \
        intl \
        gd \
        soap \
        bcmath \
        opcache \
        ldap \
        xsl

# Activer les modules Apache nécessaires
RUN a2enmod rewrite

# Copier la configuration Apache personnalisée
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Copier le code source Elgg
COPY . /var/www/html/elgg

# Copier les fichiers de config initiaux
# COPY elgg-config/ /var/www/html/elgg/elgg-config/

# Droits sur les dossiers nécessaires
RUN mkdir -p /var/elgg_data && chown -R www-data:www-data /var/elgg_data && chmod 775 /var/elgg_data

# Définir le dossier de travail
WORKDIR /var/www/html/elgg

# Installer Composer (version recommandée)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer les dépendances PHP avec Composer
RUN composer install --no-dev --no-scripts --no-progress --optimize-autoloader || true

# Donner les bons droits à Apache
RUN chown -R www-data:www-data /var/www/html/elgg

# Exposer le port HTTP
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
