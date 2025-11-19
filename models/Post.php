<?php
require_once __DIR__ . '/../config/database.php';

class Post {
    private $collection;

    public function __construct() {
        $this->collection = Database::getConnection()->posts;
    }

    // Récupérer tous les posts (récent au vieux)
    public function getAll() {
        return $this->collection->find([], ['sort' => ['date' => -1]])->toArray();
    }

    // Récupérer un post
    public function getById($id) {
        try {
            return $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        } catch (Exception $e) {
            return null;
        }
    }

    // Créer un post
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
}
