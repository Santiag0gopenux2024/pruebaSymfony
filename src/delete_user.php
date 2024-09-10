<?php
require 'vendor/autoload.php';

use MongoDB\Client;

$client = new Client("mongodb://mongo:27017");
$collection = $client->test->users;

$id = $_GET['id'] ?? '';

try {
    $deleteResult = $collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    if ($deleteResult->getDeletedCount() === 0) {
        echo "Error: Usuario no encontrado";
    } else {
        header('Location: index.php');
        exit;
    }
} catch (Exception $e) {
    echo "Error al eliminar el usuario: " . $e->getMessage();
}
?>
