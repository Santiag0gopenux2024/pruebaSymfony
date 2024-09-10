<?php
require 'vendor/autoload.php';

use MongoDB\Client;

header('Content-Type: application/json');

$client = new Client("mongodb://mongo:27017");
$collection = $client->test->posts;

$posts = $collection->find()->toArray();
$response = array_map(function($post) {
    return [
        'id' => (string)$post['_id'],
        'userId' => (string)$post['userId'],
        'title' => $post['title'],
        'content' => $post['content'],
        'createdAt' => $post['createdAt']->toDateTime()->format('Y-m-d H:i:s')
    ];
}, $posts);

echo json_encode($response);
