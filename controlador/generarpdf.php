<?php
ob_start();  // Inicia el buffer de salida

include("conectar.php");
include("../pdf/fpdf.php");

// Activar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id_tarea']) && is_numeric($_GET['id_tarea'])) {
    $id_tarea = (int) $_GET['id_tarea'];

    // Consulta a la base de datos para la tarea principal
    $stmt = $pdo->prepare("SELECT * FROM tarea WHERE id_tarea = :id_tarea");
    $stmt->bindParam(':id_tarea', $id_tarea);
    $stmt->execute();

    $tarea = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($tarea) {
        // Generar PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Tarea #' . $tarea['id_tarea'], 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Tarea Principal:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Titulo: ' . htmlspecialchars($tarea['titulo']), 0, 1);
        $pdf->MultiCell(0, 10, 'Descripcion: ' . htmlspecialchars($tarea['descripcion']));
        $pdf->Cell(0, 10, 'Estatus: ' . htmlspecialchars($tarea['estatus']), 0, 1);

        // Consulta a la base de datos para las subtareas
        $stmt_subtasks = $pdo->prepare("SELECT * FROM subtarea WHERE id_tarea = :id_tarea");
        $stmt_subtasks->bindParam(':id_tarea', $id_tarea);
        $stmt_subtasks->execute();

        $subtareas = $stmt_subtasks->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($subtareas)) {
            $pdf->Ln(10); // Espacio entre tareas y subtareas
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Subtareas:', 0, 1);
            $pdf->SetFont('Arial', '', 12);

            foreach ($subtareas as $subtarea) {
                $pdf->Cell(0, 10, 'Subtarea: ' . htmlspecialchars($subtarea['titulo']), 0, 1);
                $pdf->MultiCell(0, 10, 'Descripcion: ' . htmlspecialchars($subtarea['descripcion']));
                $pdf->Cell(0, 10, 'Fecha: ' . htmlspecialchars($subtarea['fecha']) . ' - Hora: ' . htmlspecialchars($subtarea['hora']), 0, 1);
                $pdf->Ln(5); // Espacio entre subtareas
            }
        } else {
            $pdf->Ln(10);
            $pdf->Cell(0, 10, 'No hay subtareas asociadas a esta tarea.', 0, 1);
        }

        // Pie de página del PDF
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Generado por Eficia - Muchas gracias por tu preferencia :3', 0, 1, 'C');

        // Enviar encabezados y salida del PDF
        ob_clean();  // Limpia el buffer
        header('Content-Type: application/pdf');
        $pdf->Output();
        exit;
    } else {
        echo "Tarea no encontrada.";
        exit;
    }
} else {
    echo "ID de tarea no válido.";
    exit;
}
?>







