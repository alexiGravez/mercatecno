<?php
session_start();
header('Content-Type: application/json');
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['id_producto'])) {
    echo json_encode(['success' => false]);
    exit;
}

$user_id = $_SESSION['user_id'];
$id_producto = $_POST['id_producto'];

// Eliminar una unidad del producto del carrito
$stmt = $conn->prepare("DELETE FROM carrito WHERE user_id = ? AND producto_id = ? LIMIT 1");
$stmt->bind_param("ii", $user_id, $id_producto);
$stmt->execute();

// Calcular el nuevo total del carrito
$total = 0.00;
$stmt = $conn->prepare("SELECT SUM(p.precio) FROM carrito c JOIN productos p ON c.producto_id = p.id WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($suma);
$stmt->fetch();
$total = $suma ? (float)$suma : 0.00;

echo json_encode(['success' => true, 'total' => $total]);
?>

