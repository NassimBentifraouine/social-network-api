<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../utils/response.php';

class PostController {
    private $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    public function handle($id = null) {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if ($id) {
                // Récupérer UN post
                $post = $this->postModel->getById($id);
                if ($post) {
                    Response::json($post);
                } else {
                    Response::json(['error' => 'Post non trouvé'], 404);
                }
            } else {
                // Récupérer TOUS les posts
                Response::json($this->postModel->getAll());
            }
        }
        elseif ($method === 'POST') {
            // Créer un post
            $data = json_decode(file_get_contents("php://input"), true);
            Response::json($this->postModel->create($data), 201);
        }
        elseif ($method === 'DELETE' && $id) {
            // Suppr un post
            Response::json($this->postModel->delete($id));
        }
        else {
            Response::json(['message' => 'Méthode non supportée ou ID manquant'], 405);
        }
    }
}
