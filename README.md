# API RESTful PHP - Réseau Social (MongoDB)

Projet de création d'une API REST pour un réseau social, utilisant **PHP Natif** et une base de données **MongoDB Atlas**. 
Le projet suit une architecture **MVC** (Modèle-Vue-Contrôleur) pour une organisation propre et maintenable du code.

## 📋 Prérequis & Installation

### 1. Récupération du projet
```bash
gh repo clone NassimBentifraouine/social-network-api
cd social-network-api
2. Installation des dépendances
Le projet utilise le driver MongoDB pour PHP via Composer.

Bash

composer install
3. Configuration
L'API est déjà configurée pour se connecter au cluster MongoDB Atlas via le fichier config/database.php. Aucune configuration locale n'est requise.

4. Lancement du serveur
Bash

php -S localhost:8080
L'API sera accessible sur : http://localhost:8080

🚀 Documentation des Endpoints
L'API respecte les conventions REST et propose des fonctionnalités avancées d'agrégation MongoDB.

🔹 Utilisateurs (Users)
Méthode	URL	Description
GET	/users	Liste de tous les utilisateurs
GET	/users/count	Statistique : Nombre total d'inscrits
GET	/users/usernames?page=1	Pagination : Liste des pseudos (3 par page)
POST	/users	Créer un utilisateur

Exporter vers Sheets

🔹 Publications (Posts)
Méthode	URL	Description
GET	/posts	Liste de tous les posts (triés par date)
GET	/posts/count	Statistique : Nombre total de posts
GET	/posts/last-five	Filtre : Les 5 derniers posts
GET	/posts/no-comments	Filtre : Posts n'ayant aucun commentaire
GET	/posts/search?word=mot	Recherche : Trouver un post par mot-clé
GET	/posts/date-filter?type=before&date=YYYY-MM-DD	Filtre : Posts avant/après une date
GET	/posts/{id}	Récupérer un post et ses commentaires
POST	/posts	Publier un nouveau post
DELETE	/posts/{id}	Supprimer un post

Exporter vers Sheets

🔹 Interactions (Likes & Follows)
Méthode	URL	Description
GET	/likes/average?category_id=ID	Agrégation : Moyenne des likes par catégorie
POST	/likes	Liker un post (Gestion des doublons)
GET	/follows/top-three	Agrégation : Top 3 des utilisateurs les plus suivis
POST	/follows	Suivre un utilisateur
DELETE	/likes/{id}	Supprimer un like

Exporter vers Sheets

🔹 Autres (Categories & Comments)
Categories : CRUD complet disponible sur /categories

Comments : CRUD complet disponible sur /comments

📦 Modèles de Données (JSON)
Format attendu pour les requêtes POST (Body) :

User :

JSON

{
  "username": "Nassim",
  "email": "nassim@test.com",
  "password": "secretpassword"
}
Post :

JSON

{
  "content": "Mon premier post sur l'API",
  "category_id": 1,
  "user_id": 1
}
Like :

JSON

{
  "post_id": "ID_DU_POST_MONGODB",
  "user_id": 1
}
Follow :

JSON

{
  "user_id": 1, 
  "user_follow_id": 2
}
🧪 Exemples de Test (cURL)
1. Créer un utilisateur :

Bash

curl -X POST -H "Content-Type: application/json" \
-d '{"username": "UserTest", "email": "test@test.com", "password": "123"}' \
http://localhost:8080/users
2. Récupérer le Top 3 des influenceurs :

Bash

curl http://localhost:8080/follows/top-three
3. Rechercher un post contenant "Tech" :

Bash

curl "http://localhost:8080/posts/search?word=Tech"
📂 Structure du Projet
config/ : Connexion Singleton à MongoDB Atlas.

controllers/ : Logique métier et traitement des requêtes HTTP.

models/ : Intéractions avec la BDD (CRUD & Pipelines d'agrégation).

utils/ : Helpers pour le formatage des réponses JSON.
