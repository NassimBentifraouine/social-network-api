<?php
require_once __DIR__ . '/../config/database.php';

class Follow {
    private $collection;

    public function __construct() {
        $this->collection = Database::getConnection()->follows;
    }

    public function getAll() {
        return $this->collection->find()->toArray();
    }

    public function create($data) {
        if (empty($data['user_id']) || empty($data['user_follow_id'])) {
            return ['error' => 'user_id et user_follow_id requis'];
        }

        // se suivre soi-même (Nah)
        if ($data['user_id'] == $data['user_follow_id']) {
            return ['error' => 'Vous ne pouvez pas vous suivre vous-même'];
        }

        // Vérif doublon
        $exists = $this->collection->findOne([
            'user_id' => (int)$data['user_id'],
            'user_follow_id' => (int)$data['user_follow_id']
        ]);

        if ($exists) {
            return ['error' => 'Déjà suivi'];
        }

        $follow = [
            'user_id'        => (int)$data['user_id'],        // Celui qui suit
            'user_follow_id' => (int)$data['user_follow_id']  // Celui qui est suivi
        ];

        $result = $this->collection->insertOne($follow);
        return ['_id' => (string)$result->getInsertedId(), 'message' => 'Suivi ajouté'];
    }

    public function delete($id) {
        try {
            $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            return ['message' => 'Suivi retiré'];
        } catch (Exception $e) {
            return ['error' => 'ID Invalide'];
        }
    }
}
