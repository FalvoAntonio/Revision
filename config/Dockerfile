
#Dockerfile est présent pour configurer notre environnement PHP



# Choisissez une image de base appropriée selon votre projet
FROM php:8.3.20-apache

# FROM node:18  # Exemple pour un projet Node.js
# FROM php:8.1-apache  # Pour un projet PHP
# FROM python:3.10     # Pour un projet Python


# Définir le répertoire de travail dans le conteneur
# C'est ici que votre code sera placé et exécuté
# C'est le dossier principal où votre site web va se trouver à l'intérieur du conteneur.
WORKDIR /var/www/html


# Installer les dépendances
# Cette partie installe des outils supplémentaires dont PHP a besoin pour se connecter à MySQL (votre base de données).
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip


# Cela active une fonction d'Apache qui permet de créer des adresses web plus jolies.
RUN a2enmod rewrite


# Cette ligne copie tous vos fichiers (vos pages PHP, images, etc.) de votre ordinateur vers le conteneur Docker.
COPY . .

# Cela s'assure que le serveur web peut bien lire et modifier vos fichiers.
RUN chown -R www-data:www-data /var/www/html


# Cela indique que votre site web sera accessible depuis le port 80 (le port standard pour les sites web).
# Exposer le port sur lequel votre application s'exécute
EXPOSE 80


# ENSUITE
# Pour construire son image :
# Nous allons ouvrir le terminal dans le répertoire de mon projet et exécutez cette commande:
    #docker build -t mon-projet:v1 .
    # -t mon-projet:v1 : Nomme votre image "mon-projet" avec le tag "v1"
    # . : Indique que le Dockerfile se trouve dans le répertoire courant

# !!! POUR CREER L'IMAGE SUR DOCKER DESKTOP : IL FAUT TAPER:docker build -t monprojet-endless:v1 .

# Comment lancer votre environnement Docker

# Ouvrez un terminal dans le répertoire de votre projet
# Construisez et démarrez les conteneurs:

# bashdocker-compose up -d