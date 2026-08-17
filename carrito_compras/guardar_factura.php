<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_POST['numero_tarjeta'])) {
    die('Datos de pago incompletos');
}

$user_id = $_SESSION['user_id'];
$metodo_pago = 'tarjeta_credito'; 
$detalles_factura = 'Tarjeta terminada en ' . substr($_POST['numero_tarjeta'], -4);

$productos = [];
$stmt = $conn->prepare("SELECT p.id, p.nombre, p.precio FROM carrito c JOIN productos p ON c.producto_id = p.id WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total = 0;

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
    $total += $row['precio'];
}
$stmt->close();

if (empty($productos)) {
    die('El carrito está vacío');
}

// Iniciar transacción
$conn->begin_transaction();

try {
    // Crear factura
    $fecha_actual = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO facturas (user_id, total, metodo_pago, detalles_factura, fecha) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idsss", $user_id, $total, $metodo_pago, $detalles_factura, $fecha_actual);
    $stmt->execute();
    $factura_id = $stmt->insert_id;
    $stmt->close();

    // Crear detalles de factura
    $stmt = $conn->prepare("INSERT INTO detalles_factura (factura_id, producto_id) VALUES (?, ?)");
    foreach ($productos as $producto) {
        $stmt->bind_param("ii", $factura_id, $producto['id']);
        $stmt->execute();
    }
    $stmt->close();

    // Vaciar carrito
    $stmt = $conn->prepare("DELETE FROM carrito WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Confirmar transacción
    $conn->commit();

    // Mostrar factura
    header('Content-Type: text/html; charset=utf-8');
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Compra Exitosa</title>
        <link rel="stylesheet" href="styles/styles.css">

    </head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <body>
        <div class="container">
            <div class="factura-container" id="factura">
                <h2>¡Compra exitosa!</h2>
                <div class="factura-header">
                    <h3>Factura #<?= $factura_id ?></h3>
                    <p>Fecha: <?= date('d/m/Y H:i') ?></p>
                </div>
                
                <div class="factura-detalle">
                    <h4>Detalles de pago:</h4>
                    <p><strong>Método:</strong> Tarjeta de crédito</p>
                    <p><strong>Detalles:</strong> <?= $detalles_factura ?></p>
                    <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>
                </div>
                
                <div class="factura-productos">
                    <h4>Productos adquiridos:</h4>
                    <ul>
                        <?php foreach ($productos as $producto): ?>
                            <li><?= htmlspecialchars($producto['nombre']) ?> - $<?= number_format($producto['precio'], 2) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <a href="index.php" class="btn-volver">Volver a la tienda</a>
                <button onclick="guardarFacturaPDF()">Guardar Factura</button>
                
            </div>
                
            
        </div>
    </body>
    <script>
        function guardarFacturaPDF() {
            const factura = document.getElementById('factura');
            html2canvas(factura).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdf = new window.jspdf.jsPDF({
                    orientation: 'portrait',
                    unit: 'pt',
                    format: 'a4'
                });
                // Ajusta el tamaño de la imagen al ancho de la hoja
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();
                const imgWidth = pageWidth;
                const imgHeight = canvas.height * imgWidth / canvas.width;
                pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                pdf.save('factura.pdf');
            });
    }
</script>
    </html>
    <?php
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    die('Error al procesar el pago: ' . $e->getMessage());
}
?>