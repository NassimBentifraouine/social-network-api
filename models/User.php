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
}
