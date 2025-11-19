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
            if ($id === 'count') {
                Response::json(['total_posts' => $this->postModel->count()]);
            }
            elseif ($id === 'last-five') {
                Response::json($this->postModel->getLastFive());
            }
            elseif ($id === 'no-comments') {
                Response::json($this->postModel->getWithoutComments());
            }
            elseif ($id === 'search') {
                $word = $_GET['word'] ?? '';
                Response::json($this->postModel->search($word));
            }
            elseif ($id === 'date-filter') {
                // URL: /posts/date-filter?type=before&date=2024-01-01
                $type = $_GET['type'] ?? '';
                $date = $_GET['date'] ?? '';
                if ($type === 'before') Response::json($this->postModel->getBeforeDate($date));
                if ($type === 'after') Response::json($this->postModel->getAfterDate($date));
            }
            elseif ($id) {
                // Récupérer un post et ses comment (Bonus)
                $post = $this->postModel->getById($id);
                if ($post) {
                    // cherche les comment associés manuellement
                    $db = Database::getConnection();
                    $comments = $db->comments->find(['post_id' => (string)$id])->toArray();
                    $post['comments'] = $comments; // ajoute les comms dans le post
                    Response::json($post);
                } else {
                    Response::json(['error' => 'Post non trouvé'], 404);
                }
            } else {
                Response::json($this->postModel->getAll());
            }
        }
        elseif ($method === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            Response::json($this->postModel->create($data), 201);
        }
        elseif ($method === 'DELETE' && $id) {
            Response::json($this->postModel->delete($id));
        }
    }
}
