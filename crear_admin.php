<?php
require_once __DIR__ . '/conexion.php';

try {
    $stmt = $conexion->prepare('INSERT INTO usuarios (nombre, apellido, dni, email, password_hash, rol) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute(['Admin', 'River', '11111111', 'admin@riverplate.com', password_hash('River1901', PASSWORD_DEFAULT), 'admin']);
    echo "Admin creado: <b>admin@riverplate.com</b> / <b>River1901</b> — <a href='index.php'>Ir al Login</a>";
} catch (PDOException $e) {
    echo "El administrador ya existe o hubo un error.";
}