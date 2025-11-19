# 🌐 API RESTful PHP -- Social Network

### PHP Natif • MongoDB Atlas • Architecture MVC

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MongoDB](https://img.shields.io/badge/MongoDB-Atlas-47A248?logo=mongodb&logoColor=white)
![MVC](https://img.shields.io/badge/Architecture-MVC-blueviolet)
![Status](https://img.shields.io/badge/Status-Production%20Ready-success)

## 📑 Sommaire

-   Aperçu du Projet
-   Technologies
-   Installation
-   Endpoints
-   Modèles JSON
-   Exemples cURL
-   Structure du Projet

## 🚀 Aperçu du Projet

Cette API RESTful permet de gérer un réseau social complet :
utilisateurs, posts, likes, follows, commentaires, catégories.
Développée en PHP natif avec MongoDB Atlas en MVC.

## 🧰 Technologies

  Technologie     Rôle
  --------------- --------------
  PHP 8+          Backend
  MongoDB Atlas   Base NoSQL
  Composer        Dépendances
  MVC             Organisation

## ⚙️ Installation

### 1. Cloner

    gh repo clone NassimBentifraouine/social-network-api
    cd social-network-api

### 2. Dépendances

    composer install

### 3. Configuration

Aucune action requise, connexion MongoDB prête.

### 4. Serveur

    php -S localhost:8080

## 📡 Endpoints

### Users

-   GET /users\
-   GET /users/count\
-   GET /users/usernames?page=1\
-   POST /users

### Posts

-   GET /posts\
-   GET /posts/count\
-   GET /posts/last-five\
-   GET /posts/no-comments\
-   GET /posts/search?word=mot\
-   GET /posts/date-filter?type=before&date=YYYY-MM-DD\
-   POST /posts\
-   DELETE /posts/{id}

### Likes & Follows

-   GET /likes/average?category_id=ID\
-   POST /likes\
-   GET /follows/top-three\
-   POST /follows

### Categories & Comments

CRUD complet pour `/categories` et `/comments`.

## 📦 Modèles JSON

### User

``` json
{
  "username": "Nassim",
  "email": "nassim@test.com",
  "password": "monmotdepasse"
}
```

### Post

``` json
{
  "content": "Mon voyage au Japon",
  "category_id": 1,
  "user_id": 1
}
```

### Like

``` json
{
  "post_id": "ID_DU_POST_MONGODB",
  "user_id": 1
}
```

### Follow

``` json
{
  "user_id": 1,
  "user_follow_id": 2
}
```

## 🧪 Exemples cURL

### 1. Créer un utilisateur

    curl -X POST -H "Content-Type: application/json" -d '{"username":"Alice","email":"alice@test.com","password":"pass"}' http://localhost:8080/users

### 2. Top 3 influenceurs

    curl http://localhost:8080/follows/top-three

### 3. Recherche "Voyage"

    curl "http://localhost:8080/posts/search?word=Voyage"

## 📂 Structure

    config/        → Connexion MongoDB  
    controllers/   → Logique métier  
    models/        → CRUD & agrégations  
    utils/         → JSON & headers  
    index.php      → Routeur  
