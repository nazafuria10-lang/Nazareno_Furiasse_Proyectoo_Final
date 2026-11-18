<?php
session_start();
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/escapar.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: ' . (($_SESSION['usuario_rol'] ?? '') === 'admin' ? 'admin_usuarios.php' : 'panel.php'));
    exit;
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conexion->prepare('SELECT id, nombre, password_hash, rol FROM usuarios WHERE email = :email AND archivado_en IS NULL LIMIT 1');
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header('Location: ' . ($usuario['rol'] === 'admin' ? 'admin_usuarios.php' : 'panel.php'));
        exit;
    }
    $error = 'Correo o contraseña incorrectos, o la cuenta está archivada.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RiverID | Ingresá a tu cuenta</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="bg-estadio">
    <div class="auth-wrapper">
        <div class="auth-form-col">
            <div class="brand-logo">RIVER<span>ID</span></div>
            <h1>Todo el universo River en un solo lugar</h1>
            <p class="subtitle">Ingresá a tu cuenta:</p>

            <?php if ($error !== ''): ?>
                <div class="alerta alerta-error"><p><?= escapar($error) ?></p></div>
            <?php endif; ?>

            <form action="index.php" method="POST" class="form-river">
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Ingresá tu correo" value="<?= escapar($email) ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Ingresá tu contraseña" required>
                </div>
                <button type="submit" class="btn-river-rojo">INGRESÁ</button>
            </form>

            <p class="auth-link">¿Aún no tenés cuenta en RiverID? <a href="registro.php">¡Registrate acá!</a></p>
        </div>

        <div class="auth-banner-col">
            <img src="img/banner_river.png" alt="Hoy es el día. Asociate a RiverID" class="banner-river-img">
        </div>
    </div>
</body>
</html>