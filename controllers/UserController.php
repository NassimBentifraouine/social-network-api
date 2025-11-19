<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/response.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function handle($id = null) {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            // Route: /users/count
            if ($id === 'count') {
                Response::json(['total_users' => $this->userModel->count()]);
            }
            // Route: /users/usernames?page=1
            elseif ($id === 'usernames') {
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                Response::json($this->userModel->getPaginated($page));
            }
            // Route: /users (Full list)
            elseif (!$id) {
                Response::json($this->userModel->getAll());
            }
            else {
                Response::json(['message' => 'Détails user non implémenté']);
            }
        }
        elseif ($method === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            Response::json($this->userModel->create($data), 201);
        }
        else {
            Response::json(['message' => 'Méthode non supportée'], 405);
        }
    }
}
