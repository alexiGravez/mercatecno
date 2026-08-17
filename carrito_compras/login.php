<?php
session_start();
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, passw FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $hash);
    if ($stmt->fetch() && password_verify($password, $hash)) {
        $_SESSION['user_id'] = $id;
        header("Location: index.php");
        exit;
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/styleL.css">
    
</head>
<body>
    <form method="post" class="login-form">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        
        <button type="submit">Entrar</button>

    <p><a href="registro.php">¿No tienes cuenta? Regístrate aquí</a></p><br>
    <p><a href="../administrador/login.php">Administrador</a></p>
</form>
</body>
</html>
