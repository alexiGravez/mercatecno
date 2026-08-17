<?php
session_start();
require 'db.php';
if (isset($_POST['id_producto']) && isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("INSERT INTO carrito (user_id, producto_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $_SESSION['user_id'], $_POST['id_producto']);
    $stmt->execute();
}
header("Location: index.php");
