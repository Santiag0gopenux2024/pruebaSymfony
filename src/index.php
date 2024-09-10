<?php
require 'vendor/autoload.php';

use MongoDB\Client;

header('Content-Type: application/json');

$client = new Client("mongodb://mongo:27017");
$collection = $client->test->users;

$users = $collection->find()->toArray();
$response = array_map(function($user) {
    return [
        'id' => (string)$user['_id'],
        'name' => $user['name'],
        'email' => $user['email']
    ];
}, $users);

echo json_encode($response);
