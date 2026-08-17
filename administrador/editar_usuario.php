<?php
        session_start();
        require 'db.php';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['idUs'];
    $email = $_POST['edit_mail'];
    $contraseña = password_hash($_POST['edit_pass'], PASSWORD_DEFAULT);
    

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar los datos
    if (empty($id) || empty($email) || empty($contraseña)/*|| empty($_FILES['edit_imagen'])*/) {
        $error = "Todos los campos son obligatorios.";
    } else {
        // Actualizar el producto en la base de datos
        $stmt = $conn->prepare("UPDATE usuarios SET email=?, passw=? WHERE id=?");
        $stmt->bind_param("ssi", $email, $contraseña, $id);

        if ($stmt->execute()) {
            header("Location: usuarios.php");
            exit();
        } else {
            $error = "Error al actualizar el usuario.";
        }
    }

    // Si no es una solicitud POST, redirigir a productos.php
    
}

    



}          
?>