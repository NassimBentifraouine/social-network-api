<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/response.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function handle() {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $users = $this->userModel->getAll();
            Response::json($users);
        }
        elseif ($method === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->userModel->create($data);
            Response::json($result, 201);
        }
        else {
            Response::json(['message' => 'Méthode non supportée'], 405);
        }
    }
}
