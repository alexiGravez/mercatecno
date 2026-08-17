<?php
    session_start();
    require 'db.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Transacciones</title>
    <link rel="icon" href="image/opia.ico">
    <link rel="stylesheet" href="stylos/stylo.css">
</head>
<body>
    <header>
        <h2>Administrador de tiendita</h2><br><hr>
        <nav class="header-cont">
            <a href="index.php"><img src="image/homa.png" alt=""></a>
            <a href="productos.php">Stock</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="ventas.php">Ventas</a>
            <a href="transacciones.php">Transacciones</a>
        </nav>

    </header>
    <section>
        <?php
            echo "<h2>Administrador</h2>";
            $sql = "SELECT * FROM bitacora WHERE usuario = 'root' || usuario='adminTienda'ORDER BY modificado DESC";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
        
            echo "<table class='album table-scroll-wrapper'>
                <tr id='table-header'>
                <th>Id</th>
                <th>operacion</th>
                <th>usuario</th>
                <th>host</th>
                <th>fecha y hora</th>
                <th>tabla</th>
                </tr>";
                
            while ($row = $result->fetch_assoc()) {
                echo "<tr>".
                "<td>{$row['id_bitacora']}</td>".
                "<td>{$row['operacion']}</td>".
                "<td>{$row['usuario']}</td>".
                "<td>{$row['host']}</td>".
                "<td>{$row['modificado']}</td>".
                "<td>{$row['tabla']}</td>".
                "</tr>";

            }
            echo "</table>";
        } else {
            echo "Sin cambios";
        }

        echo "<h2>Cliente</h2>";
            $sql = "SELECT * FROM bitacora WHERE usuario = 'cliente' ORDER BY modificado DESC";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
        
            echo "<table class='album table-scroll-wrapper'>
                <tr id='table-header'>
                <th>Id</th>
                <th>operacion</th>
                <th>usuario</th>
                <th>host</th>
                <th>fecha y hora</th>
                <th>tabla</th>
                </tr>";
                
            while ($row = $result->fetch_assoc()) {
                echo "<tr>".
                "<td>{$row['id_bitacora']}</td>".
                "<td>{$row['operacion']}</td>".
                "<td>{$row['usuario']}</td>".
                "<td>{$row['host']}</td>".
                "<td>{$row['modificado']}</td>".
                "<td>{$row['tabla']}</td>".
                "</tr>";

            }
            echo "</table>";
        } else {
            echo "Sin cambios";
        }
        ?>
        
    </section>
</body>
</html>
