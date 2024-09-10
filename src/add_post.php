<?php
require 'vendor/autoload.php';

use MongoDB\Client;

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client = new Client("mongodb://mongo:27017");
    $postsCollection = $client->test->posts;
    $usersCollection = $client->test->users;

    $data = json_decode(file_get_contents('php://input'), true);

    $userId = isset($data['userId']) ? filter_var($data['userId']) : '';
    $title = isset($data['title']) ? filter_var($data['title']) : '';
    $content = isset($data['content']) ? filter_var($data['content']) : '';

    if (empty($userId) || empty($title) || empty($content)) {
        $response['message'] = 'El ID del usuario, el título y el contenido son obligatorios.';
    } else {
        try {
            $user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($userId)]);

            if (!$user) {
                $response['message'] = 'El ID del usuario no existe en la colección de usuarios.';
            } else {
                $insertResult = $postsCollection->insertOne([
                    'userId' => new MongoDB\BSON\ObjectId($userId),
                    'title' => $title,
                    'content' => $content,
                    'createdAt' => new MongoDB\BSON\UTCDateTime()
                ]);
                $response['success'] = true;
                $response['message'] = 'Post agregado exitosamente.';
            }
        } catch (Exception $e) {
            $response['message'] = 'Error al agregar el post: ' . $e->getMessage();
        }
    }

    echo json_encode($response);
}
