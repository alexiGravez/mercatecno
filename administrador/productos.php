<?php
// Conexión a la base de datos
session_start();
require 'db.php';

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $stock = intval($_POST['stock']);

       // Procesar imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $permitidos = ['jpg', 'jpeg', 'png', 'gif','webp'];
        $img_tipo = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

        if (in_array($img_tipo, $permitidos)) {
            $img_binario = file_get_contents($_FILES['imagen']['tmp_name']);
            $img_binario = $conn->real_escape_string($img_binario);
            
                // Guardar en la base de datos
                $sql = "INSERT INTO productos (nombre, precio, descripcion, stock,img) VALUES ('$nombre', $precio, '$descripcion', '$stock','$img_binario')";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Producto registrado correctamente.');</script>";
                    
                } else {
                    echo "<script>alert('Producto registrado correctamente. ". $conn->error."');</script>";
                }
            
        } else {
            echo "Formato de imagen no permitido.";
        }
    } else {
        echo "Debe seleccionar una imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrador - Productos</title>
    <link rel="stylesheet" href="stylos/stylo.css">
    <link rel="icon" href="image/opia.ico"> 
</head>

<body>
    <header>
        <h2>Productos</h2><br><hr>
        <nav class="header-cont">
            <a href="index.php"><img src="image/homa.png" alt=""></a>
            <a href="productos.php">Stock</a>
            <a href="usuarios.php">Usuarios</a>
            <a href="ventas.php">Ventas</a>
            <a href="transacciones.php">Transacciones</a>
        </nav>
        </nav>
        
    </header>
    <section>
        <table class="edit_P" border="2">
            <tr>
                <td>
                <h3>Insertar Producto</h3>
                    <form method="post" enctype="multipart/form-data" class="insert">
                        <label>Nombre:</label><br>
                        <input type="text" name="nombre" required><br><br>
                        <label>Precio:</label><br>
                        <input type="number" step="0.01" name="precio" required><br><br>
                        <label>Stock:</label><br>
                        <input type="number" step="0.01" name="stock" required><br><br>
                        <label>Descripción:</label><br>
                        <textarea name="descripcion" required></textarea><br><br>
                        <label>Imagen:</label><br>
                        <input type="file" name="imagen" accept="image/*" required><br><br>
                        <input type="submit" value="Registrar">
                        <!--limpiar campos-->
                        <button type="button" onclick="document.querySelector('.insert').reset();">Limpiar</button>
                    </form>
                </td>
                <td id="editar_producto">
                    <h3>Editar Producto</h3>
                    <form action='editar_producto.php' id='editar' method="post">
                        <input type='hidden' id='edit_id' name='idP' required><br>
                        <label>Nombre:</label>
                        <input type='text' id='edit_nombre' name='nomP' required><br><br>
                        <label>Precio:</label>
                        <input type='number' id='edit_precio' name='edit_precio' step='0.01' required><br><br>
                        <label>Stock:</label>
                        <input type='number' id='edit_stock' name='edit_stock' step='0.01' required><br><br>
                        <label>Descripción:</label>
                        <textarea id='edit_descripcion' name='edit_descripcion' required></textarea><br><br>
                        <label>Imagen:</label><br>
                        <input type="file" name="edit_imagen" accept="image/*"><br><br>
                        <input type="image" id="edit_img_preview" class="ed_img">
                        <input type='submit' value='Guardar cambios'>
                        <!--limpiar campos-->
                        <button type='button' onclick="document.getElementById('editar').reset();">Limpiar</button>
                    </form>
                <td/>
            </tr>

        </table>
    
                

    </section>
    <section>
    
        <?php
            echo "<h2>Productos en el carrito</h2>";
            $sql = "SELECT * from productos";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
        
            echo "<table class='album table-scroll-wrapper'>
                <tr id='table-header'>
                <th>Id</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Muesrta</th>
                <th>Acciones</th>
                </tr>";
                
            while ($row = $result->fetch_assoc()) {
                $id_P = $row['id'];
                echo "<tr>".
                "<td>{$row['id']}</td>".
                "<td>{$row['nombre']}</td>".
                "<td>{$row['descripcion']}</td>".
                "<td>{$row['precio']}</td>".
                "<td>{$row['stock']}</td>".
                "<td><img src='data: image/png;base64,". base64_encode($row['img'])."'></td>".
                "<td>"?>
                <div>
                    <button type='button' onclick="editarProducto(
                    '<?php echo htmlspecialchars($row['id'], ENT_QUOTES); ?>',
                    '<?php echo htmlspecialchars($row['nombre'], ENT_QUOTES); ?>',
                    '<?php echo htmlspecialchars($row['precio'], ENT_QUOTES); ?>',
                    '<?php echo htmlspecialchars($row['stock'], ENT_QUOTES); ?>',
                    '<?php echo htmlspecialchars($row['descripcion'], ENT_QUOTES); ?>'
                    , '<?php echo base64_encode($row['img']); ?>'

                    )">Editar</button><br>
                </div>
                

                <form method="post" action="eliminar_producto.php" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                    <input type='hidden' name='idPe' value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES); ?>">
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
        
    <footer>

    </footer>

        <script>
            function editarProducto(id, nombre, precio, stock, descripcion, img) {
                document.getElementById('editar').reset(); // Limpiar el formulario antes de rellenar
                
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_nombre').value = nombre;
                document.getElementById('edit_precio').value = precio;
                document.getElementById('edit_stock').value = stock;
                document.getElementById('edit_descripcion').value = descripcion;
                if (img) {
                    // Mostrar vista previa de la imagen en el formulario de edición
                    let imgPreview = document.getElementById('edit_img_preview');
                    if (!imgPreview) {
                        imgPreview = document.createElement('img');
                        imgPreview.id = 'edit_img_preview';
                        imgPreview.style.maxWidth = '100px';
                        imgPreview.style.display = 'block';
                        document.getElementById('edit_img').insertAdjacentElement('afterend', imgPreview);
                    }
                    imgPreview.src = 'data:image/png;base64,' + img;
                }

                // Desplazar la vista al formulario de edición
                document.getElementById('editar_producto').scrollIntoView({ behavior: 'smooth' });

            }
            </script>




</body>
</html>