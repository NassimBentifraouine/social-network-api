<?php
require_once __DIR__ . '/../models/Follow.php';
require_once __DIR__ . '/../utils/response.php';

class FollowController {
    private $model;

    public function __construct() {
        $this->model = new Follow();
    }

    public function handle($id = null) {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            // Route: /follows/top-three
            if ($id === 'top-three') {
                Response::json($this->model->getTopThree());
            }
            // Route: /follows/count-followers?user_id=1
            elseif ($id === 'count-followers') {
                $uid = (int)($_GET['user_id'] ?? 0);
                Response::json(['followers' => $this->model->countFollowers($uid)]);
            }
            // Route: /follows/count-following?user_id=1
            elseif ($id === 'count-following') {
                $uid = (int)($_GET['user_id'] ?? 0);
                Response::json(['following' => $this->model->countFollowing($uid)]);
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
