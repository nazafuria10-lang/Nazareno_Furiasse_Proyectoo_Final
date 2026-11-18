<?php
// eliminar_usuario.php
session_start();
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_SESSION['usuario_rol'] ?? '') !== 'admin' || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    exit('Acción no autorizada.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id && $id > 0 && $id !== (int)$_SESSION['usuario_id']) {
    $conexion->prepare('DELETE FROM usuarios WHERE id = :id')->execute(['id' => $id]);
}

header('Location: ' . (($_POST['origen'] ?? '') === 'archivados' ? 'usuarios_archivados.php' : 'admin_usuarios.php'));
exit;