<?php

require 'vendor/autoload.php';

use MongoDB\Client;

try {
    $client = new Client("mongodb://mongo:27017");

    $collection = $client->test->users;

    $insertResult = $collection->insertOne(['name' => 'John Doe', 'email' => 'john.doe@example.com']);

    echo "Inserted with Object ID '{$insertResult->getInsertedId()}'";

    $user = $collection->findOne(['name' => 'John Doe']);
    if ($user) {
        echo "Found user: " . $user['name'] . " with email: " . $user['email'];
    } else {
        echo "User not found";
    }
} catch (Exception $e) {
    echo "An error occurred: ";
}

?>
