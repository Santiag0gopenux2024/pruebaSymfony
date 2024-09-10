<?php
require 'vendor/autoload.php';

use MongoDB\Client;

$client = new Client("mongodb://mongo:27017");
$collection = $client->test->users;

$id = $_GET['id'] ?? '';
$user = $collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var($_POST['name']);
    $email = filter_var($_POST['email']);

    try {
        $updateResult = $collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => ['name' => $name, 'email' => $email]]
        );
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        echo "Error al actualizar el usuario: " . $e->getMessage();
    }
}

if (!$user) {
    echo "Usuario no encontrado";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar usuario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<h1>Actualizar usuario</h1>
<form method="post">
    <label for="name">Nombre:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>
    <input type="submit" value="Actualizar">
</form>
<a href="index.php">Volver</a>
</body>
</html>
