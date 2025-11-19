<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    exit;
}

require_once __DIR__ . '/utils/response.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/PostController.php';
require_once __DIR__ . '/controllers/CategoryController.php';
require_once __DIR__ . '/controllers/CommentController.php';
require_once __DIR__ . '/controllers/LikeController.php';
require_once __DIR__ . '/controllers/FollowController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');
$segments = explode('/', $uri);

$resource = $segments[0] ?? '';
$id = $segments[1] ?? null;

try {
    switch ($resource) {
        case '':
        case 'index.php':
            Response::json(['message' => 'API en ligne']);
            break;

        case 'users':
            $controller = new UserController();
            $controller->handle();
            break;

        case 'posts':
            $controller = new PostController();
            $controller->handle($id);
            break;

        case 'categories':
            (new CategoryController())->handle($id);
            break;

        case 'comments':
            (new CommentController())->handle($id);
            break;

        case 'likes':
            (new LikeController())->handle($id);
            break;

        case 'follows':
            (new FollowController())->handle($id);
            break;

        default:
            Response::json(['error' => 'Ressource introuvable'], 404);
            break;
    }
} catch (Exception $e) {
    Response::json(['error' => $e->getMessage()], 500);
}
