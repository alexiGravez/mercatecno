<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

$stmt = $conn->prepare("SELECT COUNT(*) FROM carrito WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count == 0) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT SUM(p.precio) FROM carrito c JOIN productos p ON c.producto_id = p.id WHERE c.user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($total);
$stmt->fetch();
$stmt->close();
$total = $total ? $total : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceso de Pago</title>
    <link rel="stylesheet" href="styles/factura.css">
</head>
<body>
    <div class="container">
        <div class="pago-container">
            <h2>Proceso de Pago</h2>
            <div class="resumen-compra">
                <h3>Resumen de tu compra</h3>
                <p>Total a pagar: <strong>$<?= number_format($total, 2) ?></strong></p>
            </div>
            
            <form id="form-pago" action="guardar_factura.php" method="post">
                <input type="hidden" name="metodo_pago" value="tarjeta_credito">
                
                <div class="form-group">
                    <label for="numero_tarjeta">Número de tarjeta:</label>
                    <input type="text" id="numero_tarjeta" name="numero_tarjeta" pattern="[0-9]{16}" maxlength="16" placeholder="1234 5678 9012 3456" required>
                </div>
                
                <div class="form-group">
                    <label for="nombre_tarjeta">Nombre en la tarjeta:</label>
                    <input type="text" id="nombre_tarjeta" name="nombre_tarjeta" placeholder="Nombre como aparece en la tarjeta" required>
                </div>
                
                <div class="form-group-row">
                    <div class="form-group">
                        <label for="expiracion_tarjeta">Fecha de expiración:</label>
                        <input type="text" id="expiracion_tarjeta" name="expiracion_tarjeta" placeholder="MM/AA" pattern="\d{2}/\d{2}" required>
                    </div>
                    <div class="form-group">
                        <label for="cvv_tarjeta">CVV:</label>
                        <input type="text" id="cvv_tarjeta" name="cvv_tarjeta" pattern="[0-9]{3,4}" maxlength="4" placeholder="123" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn-pagar" id="btn-finalizar-compra">
                        Finalizar compra
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.getElementById('form-pago').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const numero = document.getElementById('numero_tarjeta').value;
        const nombre = document.getElementById('nombre_tarjeta').value;
        const expiracion = document.getElementById('expiracion_tarjeta').value;
        const cvv = document.getElementById('cvv_tarjeta').value;
        
        if (!numero || numero.length !== 16) {
            alert('Por favor ingrese un número de tarjeta válido (16 dígitos)');
            return;
        }
        
        if (!nombre) {
            alert('Por favor ingrese el nombre como aparece en la tarjeta');
            return;
        }
        
        if (!expiracion || !/^\d{2}\/\d{2}$/.test(expiracion)) {
            alert('Por favor ingrese una fecha de expiración válida (MM/AA)');
            return;
        }
        
        if (!cvv || cvv.length < 3) {
            alert('Por favor ingrese un CVV válido (3 o 4 dígitos)');
            return;
        }
        
        const btn = document.getElementById('btn-finalizar-compra');
        btn.disabled = true;
        btn.textContent = 'Procesando...';
        
        this.submit();
    });
    </script>
</body>
</html>