<?php
require '../controlador/auth.php';
require '../controlador/conectar.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php'); // Si no está logueado, redirigir al login
    exit;
}

// Inserción de la tarea principal y sus subtareas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos de la tarea principal
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $id_usuario = $_SESSION['id_usuario'];

    // Insertar la tarea principal en la base de datos
    $stmt = $pdo->prepare('INSERT INTO tarea (id_usuario, titulo, descripcion) VALUES (?, ?, ?)');
    $stmt->execute([$id_usuario, $titulo, $descripcion]);

    // Obtener el ID de la tarea recién insertada
    $id_tarea = $pdo->lastInsertId();

    // Insertar las subtareas
    if (!empty($_POST['subtask_title'])) {
        foreach ($_POST['subtask_title'] as $index => $subtask_title) {
            $subtask_description = $_POST['subtask_description'][$index];
            $subtask_date = $_POST['subtask_date'][$index];
            $subtask_time = $_POST['subtask_time'][$index];

            // Insertar la subtarea
            $stmt_subtask = $pdo->prepare('INSERT INTO subtarea (id_tarea, titulo, descripcion, fecha, hora) VALUES (?, ?, ?, ?, ?)');
            $stmt_subtask->execute([$id_tarea, $subtask_title, $subtask_description, $subtask_date, $subtask_time]);
        }
    }

    // Redirigir después de la inserción
    header('Location: tasks.php');
    exit;
}

// Obtener todas las tareas del usuario logueado
$stmt = $pdo->prepare('SELECT * FROM tarea WHERE id_usuario = :id_usuario');
$stmt->execute(['id_usuario' => $_SESSION['id_usuario']]);
$tareas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tus tareas</title>
    <link rel="stylesheet" href="tarea.css">
</head>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<body>
    <div class="tasks-container">
        <h1>Tus tareas</h1>

        <form action="tasks.php" method="POST">
            <h2>Agregar nueva tarea</h2>
            <input type="text" name="titulo" placeholder="Título de la tarea" required>
            <textarea name="descripcion" placeholder="Descripción de la tarea"></textarea>
            
            <h3>Subtareas</h3>
            <div id="subtasks-container">
                <div class="subtask">
                    <input type="text" name="subtask_title[]" placeholder="Título de la subtarea" required>
                    <textarea name="subtask_description[]" placeholder="Descripción de la subtarea"></textarea>
                    <input type="date" name="subtask_date[]" required>
                    <input type="time" name="subtask_time[]" required>
                </div>
            </div>
            <button type="button" id="add-subtask">Agregar subtarea</button>
            <button type="submit">Agregar tarea</button>
        </form>

        <ul>
            <?php if (empty($tareas)): ?>
                <li>No se encontraron tareas</li>
            <?php else: ?>
                <?php foreach ($tareas as $tarea): ?>
                    <li>
                        <h3><?= htmlspecialchars($tarea['titulo']) ?></h3>
                        <p><?= htmlspecialchars($tarea['descripcion']) ?></p>
                        <p>Status: <?= htmlspecialchars($tarea['estatus']) ?></p>
                        
                        <!-- Mostrar las subtareas relacionadas con la tarea -->
                        <?php
                        $stmt_subtasks = $pdo->prepare('SELECT * FROM subtarea WHERE id_tarea = :id_tarea');
                        $stmt_subtasks->execute(['id_tarea' => $tarea['id_tarea']]);
                        $subtareas = $stmt_subtasks->fetchAll();

                        if (!empty($subtareas)) {
                            echo '<ul>';
                            foreach ($subtareas as $subtarea) {
                                echo '<li>';
                                echo '<strong>' . htmlspecialchars($subtarea['titulo']) . '</strong>';
                                echo '<p>' . htmlspecialchars($subtarea['descripcion']) . '</p>';
                                
                                echo '<p><strong>Date:</strong> ' . htmlspecialchars($subtarea['fecha']) . ' <strong>Time:</strong> ' . htmlspecialchars($subtarea['hora']) . '</p>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo '<p>No subtasks found</p>';
                        }
                        ?>

                        <!-- Enlaces para marcar como completada o generar PDF -->
                        <a href="../controlador/editar_tarea.php?id_tarea=<?= $tarea['id_tarea'] ?>">Marcar "Completar"</a>
                        <a href="../controlador/generarpdf.php?id_tarea=<?= $tarea['id_tarea'] ?>">Generar PDF</a>

                        <form action="../controlador/eliminar_tarea.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id_tarea" value="<?= $tarea['id_tarea'] ?>">
                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta tarea?');">Eliminar</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <script>
        // Agregar dinámicamente más campos para subtareas
        document.getElementById('add-subtask').addEventListener('click', function() {
            const container = document.getElementById('subtasks-container');
            const newSubtask = document.createElement('div');
            newSubtask.classList.add('subtask');
            newSubtask.innerHTML = `
                <input type="text" name="subtask_title[]" placeholder="Subtask Title" required>
                <textarea name="subtask_description[]" placeholder="Subtask Description"></textarea>
                <input type="date" name="subtask_date[]" required>
                <input type="time" name="subtask_time[]" required>
            `;
            container.appendChild(newSubtask);
        });
    </script>

</body>
</html>



