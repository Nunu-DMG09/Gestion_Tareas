<?php
require '../controlador/conectar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Generar un hash para la contraseña
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare('INSERT INTO usuario (username, contra) VALUES (:username, :password)');
        $stmt->execute(['username' => $username, 'password' => $hashedPassword]);
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        $error = 'Username already exists';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create an Account</title>
    <link rel="stylesheet" href="estile.css">
</head>
<body>
    <div class="form-container">
        <h1>Registro</h1>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Nombre de Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarse</button>
        </form>
        <br>
        <a href="login.php" class="btn-volver">
        <i class="fas fa-home"></i> Volver 
        </a>
    </div>
</body>
</html>