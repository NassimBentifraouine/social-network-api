<?php
require_once __DIR__ . '/../config/database.php';

class Category {
    private $collection;

    public function __construct() {
        $this->collection = Database::getConnection()->categories;
    }

    public function getAll() {
        return $this->collection->find()->toArray();
    }

    public function create($data) {
        if (empty($data['name'])) {
            return ['error' => 'Nom de catégorie requis'];
        }
        $result = $this->collection->insertOne(['name' => $data['name']]);
        return ['_id' => (string)$result->getInsertedId(), 'message' => 'Catégorie créée'];
    }

    public function delete($id) {
        try {
            $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
            return ['message' => 'Catégorie supprimée'];
        } catch (Exception $e) {
            return ['error' => 'ID Invalide'];
        }
    }
}
