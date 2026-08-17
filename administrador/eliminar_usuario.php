<?php
session_start();
require 'db.php';
//eliminar usuario de la tabla tienda.usuarios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['idU'];

    // Validar el ID del usuario
    if (empty($id)) {
        $error = "El ID del usuario es obligatorio.";
    } else {
        // Preparar la consulta para eliminar el usuario
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: usuarios.php");
            exit();
        } else {
            $error = "Error al eliminar el usuario: " . $conn->error;
        }
    }
     header("Location: usuarios.php");
            exit();
}


?>