<?php
require 'auth.php';
require 'conectar.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../vista/login.php'); // Si no está logueado, redirigir al login
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos de la tarea principal
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $id_usuario = $_SESSION['id_usuario'];

    // Insertar la tarea principal en la base de datos
    $stmt = $pdo->prepare('INSERT INTO tarea (id_usuario, titulo, descripcion) VALUES (?, ?, ?)');
    $stmt->execute([$id_usuario, $titulo, $descripcion]);

    // Obtener el ID de la tarea principal insertada
    $id_tarea = $pdo->lastInsertId();

    // Insertar las subtareas asociadas a esta tarea
    if (!empty($_POST['subtask_title'])) {
        foreach ($_POST['subtask_title'] as $index => $subtask_title) {
            $subtask_description = $_POST['subtask_description'][$index];
            $subtask_date = $_POST['subtask_date'][$index];
            $subtask_time = $_POST['subtask_time'][$index];

            // Insertar las subtareas en la base de datos
            $stmt_subtask = $pdo->prepare('INSERT INTO subtarea (id_tarea, titulo, descripcion, fecha, hora) VALUES (?, ?, ?, ?, ?)');
            $stmt_subtask->execute([$id_tarea, $subtask_title, $subtask_description, $subtask_date, $subtask_time]);
        }
    }

    // Redirigir a la página de tareas después de insertar
    header('Location: ../vista/tasks.php');
    exit;
}

?>
