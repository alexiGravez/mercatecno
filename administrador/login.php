<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Usuario y contraseña de administrador
    $admin_email = 'adminTienda';
    $admin_password = 'admTienda';

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: login.php');
        exit;
    } else {
        $error = "Correo o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tienda Login - Admin</title>
    <link rel="stylesheet" href="stylos/styles.css">
    <link rel="stylesheet" href="stylos/styleL.css">
    
</head>
<body>
    <form method="post" class="login-form" action="index.php">
        <h2>Administrador</h2>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <input type="text" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    <p><a href="../carrito_compras/login.php">Regresar</a></p>
</form>
</body>
</html>