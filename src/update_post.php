<?php
require 'vendor/autoload.php';

use MongoDB\Client;

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

$client = new Client("mongodb://mongo:27017");
$collection = $client->test->posts;

$id = $_GET['id'] ?? '';

if (!$id || !preg_match('/^[a-f\d]{24}$/i', $id)) {
    $response['message'] = 'ID inválido';
    echo json_encode($response);
    exit;
}

$post = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        $response['message'] = 'Error en el formato JSON enviado';
        echo json_encode($response);
        exit;
    }

    $title = isset($data['title']) ? filter_var($data['title']) : '';
    $content = isset($data['content']) ? filter_var($data['content']) : '';

    if (empty($title) || empty($content)) {
        $response['message'] = 'El título y el contenido son obligatorios.';
    } else {
        try {
            $updateResult = $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($id)],
                ['$set' => ['title' => $title, 'content' => $content]]
            );
            $response['success'] = true;
            $response['message'] = 'Post actualizado exitosamente.';
        } catch (Exception $e) {
            $response['message'] = 'Error al actualizar el post: ' . $e->getMessage();
        }
    }

    echo json_encode($response);
    exit;
}

if (!$post) {
    $response['message'] = 'Post no encontrado';
    echo json_encode($response);
    exit;
}
