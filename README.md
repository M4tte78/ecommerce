# Valorant Agent Favorites - Site E-commerce

## Description du projet

Ce projet est un site web qui permet aux utilisateurs de découvrir les agents du jeu **Valorant** et de les ajouter à leur liste de favoris. Chaque agent est présenté sous forme de carte, avec des informations détaillées sur ses compétences et sa description. Les utilisateurs doivent s'inscrire et se connecter pour pouvoir gérer leur liste de favoris.

## Fonctionnalités

- **Affichage des agents** : Le site récupère les données des agents de Valorant via l'API officielle ([Valorant API](https://valorant-api.com)) et les affiche sous forme de cartes comprenant l'image, le nom, la description et les compétences de chaque agent.
- **Inscription/Connexion des utilisateurs** : Les utilisateurs peuvent s'inscrire et se connecter pour accéder à leurs favoris.
- **Gestion des favoris** : Une fois connectés, les utilisateurs peuvent ajouter des agents à leur liste de favoris, qui est sauvegardée dans une base de données.
  
## Comment fonctionne le site ?

1. **Page d'accueil** : Lorsqu'un utilisateur arrive sur le site, il voit une liste d'agents sous forme de cartes. Chaque carte affiche :
   - L'image de l'agent
   - Le nom de l'agent
   - Une courte description
   - La liste des compétences de l'agent
   - Un bouton "Ajouter aux favoris" (disponible uniquement pour les utilisateurs connectés)

2. **Inscription et connexion** : 
   - Un utilisateur doit s'inscrire pour créer un compte en fournissant un nom d'utilisateur, un email et un mot de passe.
   - Une fois inscrit, il peut se connecter à son compte pour accéder à la fonctionnalité des favoris.

3. **Ajout aux favoris** :
   - Lorsqu'un utilisateur est connecté, il peut cliquer sur le bouton "Ajouter aux favoris" pour enregistrer un agent dans sa liste de favoris.
   - Les agents sont stockés dans la base de données, et les utilisateurs peuvent retrouver leur liste à tout moment.

## Schéma de la base de donnée
![alt text](image.png)

## Données récupérées des agents via l'API

L'API Valorant renvoie une grande quantité d'informations sur chaque agent, notamment :
- **Nom de l'agent** (`displayName`)
- **Description** (`description`)
- **Icone de l'agent** (`displayIcon`)
- **Liste des compétences** (`abilities`), chaque compétence ayant :
  - Un nom (`displayName`)
  - Une description (`description`)
  - Une icône de compétence (`displayIcon`)

Ces informations sont affichées sous forme de cartes pour chaque agent sur le site.

## Technologies utilisées

- **Frontend** : HTML, CSS, JavaScript
- **Backend** : PHP
- **Base de données** : MySQL (via XAMPP)
- **API** : [Valorant API](https://valorant-api.com)

## Installation et configuration

1. Clonez le dépôt du projet.
2. Configurez une base de données MySQL avec les tables `users`, `agents`, et `favorites` (voir fichier `db.sql`).
3. Configurez un environnement PHP et MySQL avec XAMPP ou un autre serveur local.
4. Modifiez le fichier de connexion à la base de données (`db_connection.php`) pour qu'il corresponde à votre configuration locale.
5. Lancez le projet via votre serveur local (XAMPP par exemple).

## Auteur

Ce projet a été réalisé dans le cadre d'un projet scolaire avec une API de jeu vidéo pour apprendre à intégrer des APIs externes et gérer des utilisateurs et des favoris dans une base de données.
