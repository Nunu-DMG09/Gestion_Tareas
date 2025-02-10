<?php
require '../controlador/conectar.php'; //CAMBIAR LA CONEXION BASE DE DATOS
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['contra'])) {
        $_SESSION['id_usuario'] = $user['id_usuario'];
        header('Location: tasks.php');
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <main>
        <h2>Iniciar Sesion</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label for="username">Nombre de Usuario:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Iniciar Sesion</button>
        </form>
        <br>
        <a href="index.html" class="btn-volver">
        <i class="fas fa-home"></i> Volver al Inicio
        </a>
        <p>¿No tienes una cuenta? <a href="register.php"> Crear una :3 </a></p>
    </main>
</body>
</html>