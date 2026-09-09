<?php
require_once 'config/database.php';

// Generar el HASH seguro para la contraseña '1234'
$password = '1234';
$hash = password_hash($password, PASSWORD_BCRYPT);

$email = 'admin@dialogoydesarrollo.com.pe';
$nombres = 'Administrador';
$ap_paterno = 'Principal';
$ap_materno = 'Sistema';
$rol = 'admin';

// Comprobar si el usuario ya existe
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
$stmt->execute([':email' => $email]);
$usuario = $stmt->fetch();

if ($usuario) {
    // Si existe, actualizamos su contraseña al hash de '1234'
    $stmt = $pdo->prepare("UPDATE usuarios SET password_hash = :hash WHERE email = :email");
    $stmt->execute([':hash' => $hash, ':email' => $email]);
    echo "¡Contraseña del usuario admin actualizada con éxito a '1234'!";
} else {
    // Si no existe, lo insertamos
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombres, ap_paterno, ap_materno, email, password_hash, rol) VALUES (:nombres, :ap_paterno, :ap_materno, :email, :hash, :rol)");
    $stmt->execute([
        ':nombres' => $nombres,
        ':ap_paterno' => $ap_paterno,
        ':ap_materno' => $ap_materno,
        ':email' => $email,
        ':hash' => $hash,
        ':rol' => $rol
    ]);
    echo "¡Usuario 'admin@dialogoydesarrollo.com.pe' creado con éxito con clave '1234'!";
}