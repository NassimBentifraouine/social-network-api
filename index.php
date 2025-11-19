<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    exit;
}

require_once __DIR__ . '/utils/response.php';
require_once __DIR__ . '/controllers/UserController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');

try {
    switch ($uri) {
        case '':
        case 'index.php':
            Response::json(['message' => 'API en ligne']);
            break;

        case 'users':
            $controller = new UserController();
            $controller->handle();
            break;

        default:
            Response::json(['error' => 'Page introuvable'], 404);
            break;
    }
} catch (Exception $e) {
    Response::json(['error' => $e->getMessage()], 500);
}
