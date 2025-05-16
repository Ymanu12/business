# Utilise une image officielle PHP avec FPM
FROM php:8.2-fpm

# Installe les extensions et dépendances nécessaires
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www

# Copier les fichiers de l'application dans le conteneur
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Donner les bons droits
RUN chmod -R 755 storage && chmod -R 755 bootstrap/cache

# Génère la clé si elle n'est pas définie
# (tu peux aussi la générer en local et la définir via ENV si tu préfères)
# RUN php artisan key:generate

# Exposer le port Laravel
EXPOSE 8000

# Commande à exécuter quand le conteneur démarre
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
