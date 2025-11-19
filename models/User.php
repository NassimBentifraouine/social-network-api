<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $collection;

    public function __construct() {
        $this->collection = Database::getConnection()->users;
    }

    public function getAll() {
        return $this->collection->find()->toArray();
    }

    public function create($data) {
        if (!isset($data['username']) || !isset($data['email'])) {
            return ['error' => 'Données incomplètes'];
        }

        $insert = $this->collection->insertOne([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'] ?? '123456', PASSWORD_DEFAULT),
            'is_active'=> true
        ]);

        return ['_id' => (string)$insert->getInsertedId(), 'message' => 'User créé'];
    }

    // total d'inscrits
    public function count() {
        return $this->collection->countDocuments();
    }

    // pagination des pseudos (3 par page)
    public function getPaginated($page) {
        $limit = 3;
        $skip = ($page - 1) * $limit; // Calcul du décalage

        // ne récupère que le champ 'username'
        return $this->collection->find(
            [],
            [
                'limit' => $limit,
                'skip' => $skip,
                'projection' => ['username' => 1, '_id' => 0]
            ]
        )->toArray();
    }
}
