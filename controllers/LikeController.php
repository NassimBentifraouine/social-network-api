<?php
require_once __DIR__ . '/../models/Like.php';
require_once __DIR__ . '/../utils/response.php';

class LikeController {
    private $model;

    public function __construct() {
        $this->model = new Like();
    }

    public function handle($id = null) {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            Response::json($this->model->getAll());
        }
        elseif ($method === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            Response::json($this->model->create($data), 201);
        }
        elseif ($method === 'DELETE' && $id) {
            Response::json($this->model->delete($id));
        }
        else {
            Response::json(['message' => 'Action non supportée'], 405);
        }
    }
}
