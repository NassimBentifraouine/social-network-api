<?php
require_once __DIR__ . '/../config/database.php';

class Like {
    private $collection;

    public function __construct() {
        $this->collection = Database::getConnection()->likes;
    }

    public function getAll() {
        return $this->collection->find()->toArray();
    }

    public function create($data) {
        if (empty($data['post_id']) || empty($data['user_id'])) {
            return ['error' => 'post_id et user_id requis'];
        }

        // Vérifier si le like existe déjà pour éviter les doublons
        $exists = $this->collection->findOne([
            'post_id' => (string)$data['post_id'],
            'user_id' => (int)$data['user_id']
        ]);

        if ($exists) {
            return ['error' => 'Déjà liké'];
        }

        $like = [
            'post_id' => (string)$data['post_id'],
            'user_id' => (int)$data['user_id']
        ];

        $result = $this->collection->insertOne($like);
        return ['_id' => (string)$result->getInsertedId(), 'message' => 'Like ajouté'];
    }

    public function delete($id) {
        try {
            $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            return ['message' => 'Like retiré'];
        } catch (Exception $e) {
            return ['error' => 'ID Invalide'];
        }
    }
}
