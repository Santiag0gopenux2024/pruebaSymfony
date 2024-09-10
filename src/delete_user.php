<?php
require 'vendor/autoload.php';

use MongoDB\Client;

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

$client = new Client("mongodb://mongo:27017");
$collection = $client->test->users;

$id = $_GET['id'] ?? '';

try {
    $deleteResult = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    if ($deleteResult->getDeletedCount() === 0) {
        $response['message'] = 'Usuario no encontrado';
    } else {
        $response['success'] = true;
        $response['message'] = 'Usuario eliminado exitosamente.';
    }
} catch (Exception $e) {
    $response['message'] = 'Error al eliminar el usuario: ' . $e->getMessage();
}

echo json_encode($response);
