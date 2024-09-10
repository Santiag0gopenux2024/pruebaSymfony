<?php
require 'vendor/autoload.php';

use MongoDB\Client;

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

$client = new Client("mongodb://mongo:27017");
$collection = $client->test->users;

$id = $_GET['id'] ?? '';

if (!$id || !preg_match('/^[a-f\d]{24}$/i', $id)) {
    $response['message'] = 'ID inválido';
    echo json_encode($response);
    exit;
}

$user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        $response['message'] = 'Error en el formato JSON enviado';
        echo json_encode($response);
        exit;
    }

    $name = isset($data['name']) ? filter_var($data['name']) : '';
    $email = isset($data['email']) ? filter_var($data['email']) : '';

    if (empty($name) || empty($email)) {
        $response['message'] = 'El nombre y el email son obligatorios.';
    } else {
        try {
            $updateResult = $collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($id)],
                ['$set' => ['name' => $name, 'email' => $email]]
            );
            $response['success'] = true;
            $response['message'] = 'Usuario actualizado exitosamente.';
        } catch (Exception $e) {
            $response['message'] = 'Error al actualizar el usuario: ' . $e->getMessage();
        }
    }

    echo json_encode($response);
    exit;
}

if (!$user) {
    $response['message'] = 'Usuario no encontrado';
    echo json_encode($response);
    exit;
}
