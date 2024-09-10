<?php
require 'vendor/autoload.php';

use MongoDB\Client;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client = new Client("mongodb://mongo:27017");
    $collection = $client->test->users;

    $name = filter_var($_POST['name']);
    $email = filter_var($_POST['email']);

    try {
        $insertResult = $collection->insertOne(['name' => $name, 'email' => $email]);
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        echo "Error al insertar el usuario: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar usuario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<h1>Agregar nuevo usuario</h1>
<form method="post">
    <label for="name">Nombre:</label>
    <input type="text" id="name" name="name" required><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>
    <input type="submit" value="Agregar">
</form>
<a href="index.php">Volver</a>
</body>
</html>
