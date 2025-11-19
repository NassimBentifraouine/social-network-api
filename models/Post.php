<?php
require_once __DIR__ . '/../config/database.php';

class Post {
    private $collection;

    public function __construct() {
        $this->collection = Database::getConnection()->posts;
    }

    // récupérer tous les posts (récent au vieux)
    public function getAll() {
        return $this->collection->find([], ['sort' => ['date' => -1]])->toArray();
    }

    // récupérer un post
    public function getById($id) {
        try {
            return $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        } catch (Exception $e) {
            return null;
        }
    }

    // ccréer un post
    public function create($data) {
        if (empty($data['content']) || empty($data['user_id'])) {
            return ['error' => 'Le contenu et user_id sont obligatoires'];
        }

        $post = [
            'content'     => $data['content'],
            'category_id' => (int)($data['category_id'] ?? 0),
            'user_id'     => (int)$data['user_id'],
            'date'        => date('Y-m-d H:i:s') // Date actuelle automatique
        ];

        $result = $this->collection->insertOne($post);
        return ['_id' => (string)$result->getInsertedId(), 'message' => 'Post publié avec succès'];
    }

    // Suppr un post
    public function delete($id) {
        try {
            $result = $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            if ($result->getDeletedCount() === 0) {
                return ['error' => 'Post introuvable'];
            }
            return ['message' => 'Post supprimé'];
        } catch (Exception $e) {
            return ['error' => 'ID invalide'];
        }
    }

    // 5 derniers posts
    public function getLastFive() {
        return $this->collection->find([], ['sort' => ['date' => -1], 'limit' => 5])->toArray();
    }

    // osts sans commentaires
    public function getWithoutComments() {
        $pipeline = [
            // On fait une jointure (lookup) avec la table comments
            ['$lookup' => [
                'from' => 'comments',
                'localField' => '_id', // ID du post (ObjectId)
                'foreignField' => 'post_id', // post_id dans comments (String)
                // dans Comment.php, converti post_id en string.
                // dans MongoDB, _id est un ObjectId. la jointure risque d'échouer si les types diffèrent.
                // du coup les solution c'etait ca : on convertit _id en string pour la jointure
                'let' => ['postId' => ['$_toString' => '$_id']],
                'pipeline' => [
                    ['$match' => ['$expr' => ['$eq' => ['$post_id', '$$postId']]]]
                ],
                'as' => 'post_comments'
            ]],
            // garde ceux où le tableau post_comments est vide
            ['$match' => ['post_comments' => ['$size' => 0]]],
            // nettoie le résultat (on enlève le tableau vide)
            ['$project' => ['post_comments' => 0]]
        ];
        return $this->collection->aggregate($pipeline)->toArray();
    }

    // recherche par mot
    public function search($word) {
        // $regex permet de chercher "comme" le mot, 'i' pour insensible à la casse
        return $this->collection->find(['content' => ['$regex' => $word, '$options' => 'i']])->toArray();
    }

    // posts avant une date
    public function getBeforeDate($date) {
        return $this->collection->find(['date' => ['$lt' => $date]])->toArray();
    }

    // posts après une date
    public function getAfterDate($date) {
        return $this->collection->find(['date' => ['$gt' => $date]])->toArray();
    }

    // nombre total de posts
    public function count() {
        return $this->collection->countDocuments();
    }
}
