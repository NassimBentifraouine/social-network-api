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

    // Top 3 des personnes les plus suivies
    public function getTopThree() {
        $pipeline = [
            ['$group' => ['_id' => '$user_follow_id', 'count' => ['$sum' => 1]]], // Grouper par personne suivie
            ['$sort'  => ['count' => -1]], // Trier du plus grand au plus petit
            ['$limit' => 3] // Garder les 3 premiers
        ];
        return $this->collection->aggregate($pipeline)->toArray();
    }

    // followers d'un user
    public function countFollowers($userId) {
        return $this->collection->countDocuments(['user_follow_id' => (int)$userId]);
    }

    // personnes suivies par un user
    public function countFollowing($userId) {
        return $this->collection->countDocuments(['user_id' => (int)$userId]);
    }
}
