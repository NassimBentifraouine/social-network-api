# API RESTful PHP - Réseau Social (MongoDB)

Projet de création d'une API REST pour un réseau social, utilisant **PHP
Natif** et une base de données **MongoDB Atlas**. Le projet suit une
architecture **MVC** (Modèle-Vue-Contrôleur) pour une meilleure
organisation du code.

## 📋 Prérequis & Installation

### 1. Récupération du projet

``` bash
gh repo clone NassimBentifraouine/social-network-api
cd social-network-api
```

### 2. Installation des dépendances

Le projet utilise le driver MongoDB pour PHP.

``` bash
composer install
```

### 3. Configuration

L'API est déjà configurée pour se connecter au cluster MongoDB Atlas via
le fichier `config/database.php`.\
Aucune action supplémentaire n'est requise.

### 4. Lancement du serveur

``` bash
php -S localhost:8080
```

L'API sera accessible sur : **http://localhost:8080**

------------------------------------------------------------------------

## 🎁 Interface Graphique de Test (Bonus)

Un Dashboard de test est inclus dans le projet, permettant de tester
l'API sans ligne de commande.

➡️ Assurez-vous que le serveur est lancé\
➡️ Ouvrez : **http://localhost:8080/test.html**

Fonctionnalités : - création d'utilisateurs\
- publication de posts\
- affichage des réponses JSON

------------------------------------------------------------------------

## 🚀 Liste des Endpoints

### 🔹 Utilisateurs (Users)

  Méthode   URL                         Description
  --------- --------------------------- --------------------------------
  GET       `/users`                    Liste de tous les utilisateurs
  GET       `/users/count`              Nombre total d'inscrits
  GET       `/users/usernames?page=1`   Pagination : 3 pseudos / page
  POST      `/users`                    Créer un utilisateur

------------------------------------------------------------------------

### 🔹 Publications (Posts)

  -----------------------------------------------------------------------------------------------------------
  Méthode               URL                                                Description
  --------------------- -------------------------------------------------- ----------------------------------
  GET                   `/posts`                                           Liste triée par date

  GET                   `/posts/count`                                     Nombre total de posts

  GET                   `/posts/last-five`                                 5 derniers posts

  GET                   `/posts/no-comments`                               Posts sans commentaires

  GET                   `/posts/search?word=mot`                           Recherche par mot-clé

  GET                   `/posts/date-filter?type=before&date=YYYY-MM-DD`   Filtre date avant/après

  POST                  `/posts`                                           Créer un post

  DELETE                `/posts/{id}`                                      Supprimer un post
  -----------------------------------------------------------------------------------------------------------

------------------------------------------------------------------------

### 🔹 Interactions (Likes & Follows)

  ------------------------------------------------------------------------------------------
  Méthode               URL                               Description
  --------------------- --------------------------------- ----------------------------------
  GET                   `/likes/average?category_id=ID`   Moyenne des likes par catégorie

  POST                  `/likes`                          Liker un post (doublons gérés)

  GET                   `/follows/top-three`              Top 3 des utilisateurs les plus
                                                          suivis

  POST                  `/follows`                        Suivre un utilisateur
  ------------------------------------------------------------------------------------------

------------------------------------------------------------------------

### 🔹 Autres (Categories & Comments)

-   **Categories** : CRUD complet → `/categories`\
-   **Comments** : CRUD complet → `/comments`

------------------------------------------------------------------------

## 📦 Modèles de Données (JSON)

### 🧍 User

``` json
{
  "username": "Nassim",
  "email": "nassim@test.com",
  "password": "monmotdepasse"
}
```

### 📝 Post

``` json
{
  "content": "Mon voyage au Japon",
  "category_id": 1,
  "user_id": 1
}
```

### 👍 Like

``` json
{
  "post_id": "ID_DU_POST_MONGODB",
  "user_id": 1
}
```

### 👥 Follow

``` json
{
  "user_id": 1,
  "user_follow_id": 2
}
```

------------------------------------------------------------------------

## 🧪 Exemples cURL

### 1. Créer un utilisateur

``` bash
curl -X POST -H "Content-Type: application/json" \
-d '{"username": "Alice", "email": "alice@test.com", "password": "pass"}' \
http://localhost:8080/users
```

### 2. Récupérer le Top 3 influenceurs

``` bash
curl http://localhost:8080/follows/top-three
```

### 3. Rechercher un post contenant "Voyage"

``` bash
curl "http://localhost:8080/posts/search?word=Voyage"
```

------------------------------------------------------------------------

## 📂 Structure du Projet

    config/       → Connexion à la base (Singleton)
    controllers/  → Logique métier
    models/       → CRUD & agrégations MongoDB
    utils/        → Réponses JSON & Headers
    index.php     → Routeur principal
