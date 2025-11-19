<?php
require_once __DIR__ . '/../config/database.php';

class Comment {
    private $collection;

    public function __construct() {
        $this->collection = Database::getConnection()->comments;
    }

    public function getAll() {
        return $this->collection->find()->toArray();
    }

    public function create($data) {
        if (empty($data['content']) || empty($data['post_id']) || empty($data['user_id'])) {
            return ['error' => 'Données manquantes (content, post_id, user_id)'];
        }

        $comment = [
            'content' => $data['content'],
            'user_id' => (int)$data['user_id'],
            'post_id' => (string)$data['post_id'],
            'date'    => date('Y-m-d H:i:s')
        ];

        $result = $this->collection->insertOne($comment);
        return ['_id' => (string)$result->getInsertedId(), 'message' => 'Commentaire ajouté'];
    }

    public function delete($id) {
        try {
            $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            return ['message' => 'Commentaire supprimé'];
        } catch (Exception $e) {
            return ['error' => 'ID Invalide'];
        }
    }
}
