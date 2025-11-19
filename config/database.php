<?php
require_once __DIR__ . '/../vendor/autoload.php';

class Database {
    private static $db;

    public static function getConnection() {
        if (self::$db === null) {
            try {
                $uri = 'mongodb+srv://admin:root@clusternassim.redqx.mongodb.net/?retryWrites=true&w=majority';
                $client = new MongoDB\Client($uri);
                self::$db = $client->social_network;
            } catch (Exception $e) {
                die(json_encode(['error' => "Connexion impossible: " . $e->getMessage()]));
            }
        }
        return self::$db;
    }
}
