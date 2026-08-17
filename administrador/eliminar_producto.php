<?php
//eliminar producto de la tabla tienda.productos
session_start();
require 'db.php';
if (!isset($_POST['idPe'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado o ID de producto no proporcionado.']);
    exit;
}

$id_producto = $_POST['idPe'];
$stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
$stmt->bind_param("i", $id_producto);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Producto eliminado']);
    // Redirigir a la página de productos después de eliminar
    echo '<script>window.location.href = "productos.php";</script>';
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto: ' . $conn->error]);
}
$stmt->close();
$conn->close();



?>