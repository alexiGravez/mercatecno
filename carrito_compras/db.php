<?php
$host = "localhost";
$user = "cliente";
$pass = "clTienda";
$db = "tienda";
$conn = new mysqli($host, $user, $pass, $db, 3306);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>