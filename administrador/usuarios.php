
<?php
        session_start();
        require 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrador - Usuarios</title>
    <link rel="stylesheet" href="stylos/stylo.css">
    <link rel="icon" href="image/opia.ico">
</head>
<body>
    <header>
        
        <h2>Administrar usuarios</h2><br><hr>
        <nav class="header-cont">
            <a href="index.php"><img src="image/homa.png" alt=""></a>
            <a href="productos.php">Stock</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="ventas.php">Ventas</a>
            <a href="transacciones.php">Transacciones</a>
            
        </nav>
        
    </header>
    <section>
        <table class="edit_P" border="2">
            <tr>
                <td>
                    <h3>Insertar Usuario</h3>
                    <form method="post" action="registro.php">
                        <label for="nombre">Email:</label>
                        <input type="email" name="mail" id="mail" required><br><br>
                        <label for="pass">Contraseña:</label>
                        <input type="text" name="pass" id="pass" required><br><br>
                        <label for="imagen">Imagen:</label>
                        <input type="file" name="imagen" id="imagen" accept=".jpg, .jpeg, .png, .gif, .webp"><br><br>
                        <input type="submit" value="Registrar Usuario">
                        <!--limpiar campos-->
                        <button type="button" onclick="document.querySelector('form').reset();">Limpiar</button>
                    </form>
                </td>
                <td>
                    <h3>Editar Usuario</h3>
                    <form action="editar_usuario.php" id="editarU" method="post" >
                        <input type="hidden"  id="idU" name="idUs" required><br>
                        <label>Nuevo Email:</label>
                        <input type="email" id="edit_email" name="edit_mail" required><br><br>
                        <label>Nueva Contraseña:</label>
                        <input type="text" id="edit_pass" name="edit_pass"  required><br><br>
                        <label>Nueva Imagen:</label>
                        <input type="file" id="edit_imagen" name="edit_imagen" accept=".jpg, .jpeg, .png, .gif, .webp"><br><br>
                        <input type="submit" value="Guardar Cambios">
                        <!--limpiar campos-->
                        <button type="button" onclick="document.getElementById('editarU').reset();">Limpiar</button>

                    </form>
                </td>
            </tr>

        </table>

    </section>
    <section>
    
        <?php

            echo "<h2>Usuarios Clientes</h2>";
            $sql = "SELECT * from usuarios";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
        
            echo "<table border='2' class='album table-scroll-wrapper'>
                <tr>
                <th>Id</th>
                <th>Email</th>
                <th>Password</th>
                <th>img</th>
                <th>Acciones</th>
                </tr>";
        
            while ($row = $result->fetch_assoc()) {
                
                echo "<tr>".
                "<td>{$row['id']}</td>".
                "<td>{$row['email']}</td>".
                "<td>{$row['passw']}</td>".
                "<td><img src='data: image/png;base64,". base64_encode($row['img'])."'></td>".
                "<td>"?>
                <div>
                    <button type='button' onclick="editarUsuario(
                        '<?php echo htmlspecialchars($row['id'], ENT_QUOTES); ?>', 
                        '<?php echo htmlspecialchars($row['email'], ENT_QUOTES); ?>', 
                        '<?php echo htmlspecialchars(password_hash($row['passw'], PASSWORD_DEFAULT), ENT_QUOTES); ?>')"
                        >Editar</button>
                </div>
                   
                <form method="post" action="eliminar_usuario.php" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                    <input type='hidden' name='idU' value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES); ?>">
                    <button type='submit'>Eliminar</button>
                </form>
                <?php echo 
                "</td>".
                "</tr>";
            }
            echo "</table>";
        } else {
            echo "No hay productos en el carrito.";
        }
        ?>
    </section>
    <script>
        function editarUsuario(id, email, passw) {
            document.getElementById('editarU').reset();

            document.getElementById('idU').value = id;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_pass').value = passw;

            document.getElementById('editarU').scrollIntoView({ behavior: 'smooth' });
        }
    </script>

</html>