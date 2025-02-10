<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONEXION</title>
</head>
<body>
<?php
try {
    $usuario = 'root';
    $servidor = 'localhost';
    $contra = 'Rakion2016123!';  // Cambia esta contraseña si es necesario
    $base = 'entregable2';    // Nombre de tu base de datos

    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$servidor;dbname=$base", $usuario, $contra);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Manejo de errores con excepciones

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
</body>
</html>