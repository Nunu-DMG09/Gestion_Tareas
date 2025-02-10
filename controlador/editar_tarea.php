<?php
require 'conectar.php';

// Verifica si se ha recibido un id_tarea y es un número válido
if (isset($_GET['id_tarea']) && is_numeric($_GET['id_tarea'])) {
    $id_tarea = (int) $_GET['id_tarea'];

    // Asegúrate de que el valor estatus sea correcto y no tenga espacios extra
    $estatus = 'completado';  // Este es el valor que quieres insertar

    // Prepara la consulta para actualizar la tarea
    $stmt = $pdo->prepare("UPDATE tarea SET estatus = :estatus WHERE id_tarea = :id_tarea");
    $stmt->bindParam(':estatus', $estatus, PDO::PARAM_STR);
    $stmt->bindParam(':id_tarea', $id_tarea, PDO::PARAM_INT);

    // Ejecuta la consulta
    $stmt->execute();

    // Verifica si la tarea fue actualizada
    if ($stmt->rowCount() > 0) {
        header('Location: ../vista/tasks.php');
        exit;
    } else {
        echo "No se pudo actualizar la tarea o la tarea no existe.";
        exit;
    }
} else {
    echo "ID de tarea no válido.";
    exit;
}
?>


