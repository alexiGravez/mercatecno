<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require 'db.php';

// Obtener productos
$products = $conn->query("SELECT * FROM productos");

// Obtener carrito
$carrito = [];
$total = 0;
$stmt = $conn->prepare("SELECT p.id, p.nombre, p.precio, p.img FROM carrito c 
                      JOIN productos p ON c.producto_id = p.id 
                      WHERE c.user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($id, $nombre, $precio, $img);
while ($stmt->fetch()) {
    $carrito[] = ['id' => $id, 'nombre' => $nombre, 'precio' => $precio, 'img' => $img];
    $total += $precio;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <link rel="stylesheet" href="styles.css">
    <script>
    function eliminarProducto(productoId, itemIndex) {
        const formData = new FormData();
        formData.append('id_producto', productoId);

        fetch('eliminar_carrito.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const item = document.getElementById('item-' + itemIndex);
                if (item) item.remove();
                
                const totalElement = document.querySelector('.carrito-total span');
                if (totalElement) {
                    totalElement.textContent = '$' + data.total.toFixed(2);
                }
                
                const carritoList = document.querySelector('.carrito-items');
                if (carritoList && carritoList.children.length === 0) {
                    carritoList.innerHTML = '<div class="empty-cart-message">El carrito está vacío</div>';
                }
            }
        });
    }
    function comprarProducto(productoId, intemIndex)
    {
        
    }
    </script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - Producto</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <header>
        <div class="header-content">
            <a href="index.php" id="principal"><h1>Tienda Tecnologica "La Tienda numero 0 de todo SLP"</h1></a>
            <div style="display: flex; align-items: center;">
                <div class="carrito-container">
                    <button class="carrito-btn">
                        🛒 Carrito 
                        <span class="carrito-total">$<?= number_format($total, 2) ?></span>
                    </button>
                    <div class="carrito-dropdown">
                        <h3 style="margin-top: 0; margin-bottom: 15px;">Tu Carrito</h3>
                        <div class="carrito-items">
                            <?php if (empty($carrito)): ?>
                                <div class="empty-cart-message">El carrito está vacío</div>
                            <?php else: ?>
                                <?php foreach ($carrito as $index => $item): ?>
                                    <div class="carrito-item" id="item-<?= $index ?>">
                                        <span><?= htmlspecialchars($item['nombre']) ?></span>
                                        <div>
                                            <span style="margin-right: 10px;">$<?= number_format($item['precio'], 2) ?></span>
                                            <button onclick="eliminarProducto(<?= $item['id'] ?>, <?= $index ?>)">X</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="carrito-total carrito-item">
                            Total: <span>$<?= number_format($total, 2) ?></span>
                            <button onclick="">Comprar</button>
                        </div>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">Cerrar sesión</a>
            </div>
        </div>
    </header>

    



</body>
</html>