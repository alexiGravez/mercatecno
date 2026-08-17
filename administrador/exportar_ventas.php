<?php
// filepath: c:\xampp\htdocs\php\carrito_compras\administrador\exportar_ventas.php
session_start();
require 'db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=ventas.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Fecha', 'Total', 'Producto', 'Precio', 'Cantidad']);

$sql = "SELECT ultima_fecha, total_ventas, nombre, precio, cantidad FROM ventas";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['ultima_fecha'],
            $row['total_ventas'],
            $row['nombre'],
            $row['precio'],
            $row['cantidad']
        ]);
    }
}
fclose($output);
exit;
?>