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

# Copier le code source Elgg dans le dossier racine web d’Apache
COPY . /var/www/html/elgg

RUN mkdir -p /var/elgg_data && chown -R www-data:www-data /var/elgg_data && chmod 775 /var/elgg_data

#fichier de configuration
<<<<<<< HEAD
#RUN mkdir -p /var/www/html/elgg/elgg-config && chown -R www-data:www-data /var/www/html/elgg/elgg-config && chmod 755 /var/www/html/elgg/elgg-config

# Copier les fichiers de config initiaux
#COPY elgg-config/* /var/www/html/elgg/elgg-config/
=======
# RUN mkdir -p /var/www/html/elgg/elgg-config && chown -R www-data:www-data /var/www/html/elgg/elgg-config && chmod 755 /var/www/html/elgg/elgg-config

# Copier les fichiers de config initiaux
# COPY elgg-config/* /var/www/html/elgg/elgg-config/
>>>>>>> 413930836491fa59c1d3c78d649f6cf633daf9dd

# Définir le dossier de travail
WORKDIR /var/www/html/elgg

# Installer Composer (version recommandée)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer les dépendances PHP avec Composer, ignore les scripts si besoin
RUN composer install --no-dev --no-scripts --no-progress --optimize-autoloader || true

# Donner les bons droits à Apache
RUN chown -R www-data:www-data /var/www/html/elgg

# Exposer le port HTTP d’Apache
EXPOSE 80

# Démarrer Apache en mode foreground
CMD ["apache2-foreground"]
########################################Test#############################

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

# # Copier le code source Elgg
# COPY . /var/www/html/elgg

# # Copier les fichiers de config initiaux
# COPY elgg-config/ /var/www/html/elgg/elgg-config/

# # Droits sur les dossiers nécessaires
# RUN mkdir -p /var/elgg_data && \
#     chown -R www-data:www-data /var/elgg_data /var/www/html/elgg/elgg-config && \
# 	chmod -R 775 /var/www/html/elgg/elgg-config

# # Définir le dossier de travail
# WORKDIR /var/www/html/elgg

# # Installer Composer (version recommandée)
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# # Installer les dépendances PHP avec Composer
# RUN composer install --no-dev --no-scripts --no-progress --optimize-autoloader || true

# # Donner les bons droits à Apache
# RUN chown -R www-data:www-data /var/www/html/elgg

# # Exposer le port HTTP
# EXPOSE 80

# # Démarrer Apache
# CMD ["apache2-foreground"]
