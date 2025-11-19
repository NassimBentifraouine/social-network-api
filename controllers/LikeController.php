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
            if ($id === 'average') {
                // URL: /likes/average?category_id=2
                $catId = $_GET['category_id'] ?? 0;
                $avg = $this->model->getAverageByCategory($catId);
                Response::json(['average_likes' => $avg]);
            }
            else {
                Response::json($this->model->getAll());
            }
        }
        elseif ($method === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            Response::json($this->model->create($data), 201);
        }
        elseif ($method === 'DELETE' && $id) {
            Response::json($this->model->delete($id));
        }
    }
}
