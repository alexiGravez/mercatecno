<?php
    // editar_producto de la tabla tienda.productos
session_start();
require 'db.php';
// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nomP']);
    $precio = floatval($_POST['edit_precio']);
    $descripcion = $conn->real_escape_string($_POST['edit_descripcion']);
    $stock = intval($_POST['edit_stock']);
    $id = intval($_POST['idP']); // Asegúrate de que el formulario envía 'edit_id'   
       // Procesar imagen
    if (isset($_FILES['edit_imagen']) && $_FILES['edit_imagen']['error'] == 0) {
        $permitidos = ['jpg', 'jpeg', 'png', 'gif','webp'];
        $img_tipo = strtolower(pathinfo($_FILES['edit_imagen']['name'], PATHINFO_EXTENSION));
        $img_binario = file_get_contents($_FILES['edit_imagen']['tmp_name']);
        
        // Guardar en la base de datos
        $stmt = $conn->prepare("UPDATE productos SET nombre=?, precio=?, descripcion=?, stock=?, img=? WHERE id=?");
        $stmt->bind_param("sdsssi", $nombre, $precio, $descripcion, $stock, $img_binario, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Producto editado correctamente.');</script>";
        } else {
            echo "<script>alert('Error al editar el producto. ". $stmt->error."');</script>";
            }
        }
    } else {
        
            $stmt = $conn->prepare("UPDATE productos SET nombre=?, precio=?, descripcion=?, stock=? WHERE id=?");
            $stmt = $conn->prepare("UPDATE productos SET nombre=?, precio=?, descripcion=?, stock=? WHERE id=?");
            $stmt->bind_param("sdssi", $nombre, $precio, $descripcion, $stock, $id);
            if ($stmt->execute()) {
                echo "<script>alert('Producto editado correctamente.');</script>";
            } else {
                echo "<script>alert('Error al editar el producto. ". $stmt->error."');</script>";
            }
        }
        header("Location: productos.php");
            exit();

?>