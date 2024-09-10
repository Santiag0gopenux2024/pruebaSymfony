<?php
require 'vendor/autoload.php';

use MongoDB\Client;

$client = new Client("mongodb://mongo:27017");
$collection = $client->test->users;

$users = $collection->find()->toArray();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de usuarios</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body >
<center>
    <h1 style="color: red">Gestión de usuarios</h1>
    <a href="add_user.php">Agregar Usuario Nuevo</a>
    <h2>Lista de usuarios</h2>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <?php echo htmlspecialchars($user['name']); ?> - <?php echo htmlspecialchars($user['email']); ?>
                <a href="update_user.php?id=<?php echo urlencode((string)$user['_id']); ?>"><br>Actualizar<br></a>
                <a href="delete_user.php?id=<?php echo urlencode((string)$user['_id']); ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?')">Borrar</a>
            </li>
        <?php endforeach; ?>
    </ul>
</center>
</body>
</html>
