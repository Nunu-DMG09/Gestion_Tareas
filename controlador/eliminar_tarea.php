<?php
require 'conectar.php';  // Incluye el archivo para la conexión a la base de datos

// Verifica si se ha recibido el id_tarea a través del POST
if (isset($_POST['id_tarea'])) {
    $id_tarea = $_POST['id_tarea'];

    // Elimina la tarea con el id correspondiente
    $stmt = $pdo->prepare("DELETE FROM tarea WHERE id_tarea = :id_tarea");
    $stmt->execute(['id_tarea' => $id_tarea]);

    // Redirige de nuevo a la página de tareas después de eliminar
    header('Location: ../vista/tasks.php');
    exit;
} else {
    // Si no se recibe el id_tarea, redirige con un mensaje de error
    echo "No se ha recibido la tarea para eliminar.";
    exit;
}
?>
