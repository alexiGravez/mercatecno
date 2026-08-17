<?php

// Conexión a la base de datos
session_start();
require 'db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas</title>
    <link rel="icon" href="image/opia.ico">
    <link rel="stylesheet" href="stylos/stylo.css">
</head>
<body>
    <header>
        <h2>Ventas</h2><br><hr>
        <nav class="header-cont">
            <a href="index.php" id="main"><img src="image/homa.png" alt=""></a>
            <a href="productos.php">Stock</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="ventas.php">Ventas</a>
            <a href="transacciones.php">Transacciones</a>
        </nav>
    </header>
    <section>

    <section>
        <?php
            echo "<h2>Administrador</h2>";
            $sql = "SELECT * FROM ventas";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
        
            echo "<table class='album table-scroll-wrapper'>
                <tr id='table-header'>
                <th>Fecha</th>
                <th>total</th>
                <th>producto</th>
                <th>precio</th>
                <th>cantidad</th>
                </tr>";
                
            while ($row = $result->fetch_assoc()) {
                echo "<tr>".
                "<td>{$row['ultima_fecha']}</td>".
                "<td>{$row['total_ventas']}</td>".
                "<td>{$row['nombre']}</td>".
                "<td>{$row['precio']}</td>".
                "<td>{$row['cantidad']}</td>".
                "</tr>";

            }
            echo "</table>";
        } else {
            echo "Sin cambios";
        }
    
        ?>
        <form method="post" action="exportar_ventas.php">
            <button id="enviar" type="submit">Exportar CSV</button>
        </form> 

    </section>
    </section>

</body>
</html>