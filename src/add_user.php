<?php
require 'vendor/autoload.php';

use MongoDB\Client;

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client = new Client("mongodb://mongo:27017");
    $collection = $client->test->users;

    $data = json_decode(file_get_contents('php://input'), true);

    $name = isset($data['name']) ? filter_var($data['name']) : '';
    $email = isset($data['email']) ? filter_var($data['email']) : '';

    if (empty($name) || empty($email)) {
        $response['message'] = 'El nombre y el email son obligatorios.';
    } else {
        try {
            $insertResult = $collection->insertOne(['name' => $name, 'email' => $email]);
            $response['success'] = true;
            $response['message'] = 'Usuario agregado exitosamente.';
        } catch (Exception $e) {
            $response['message'] = 'Error al agregar el usuario: ' . $e->getMessage();
        }
    }

    echo json_encode($response);
}
