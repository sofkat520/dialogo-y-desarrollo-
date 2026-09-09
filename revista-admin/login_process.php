<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_input = trim($_POST['email']); // Puede ser 'admin' o el correo completo
    $password    = $_POST['password'];

    // Usamos marcadores distintos (:input1 e :input2) para evitar el error de PDO
    $sql = "SELECT * FROM usuarios 
            WHERE email = :input1 OR nombres = :input2 
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':input1' => $login_input,
        ':input2' => $login_input
    ]);
    
    $usuario = $stmt->fetch();

    // Validar contraseña con Hash
    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombres'] . ' ' . $usuario['ap_paterno'];
        $_SESSION['usuario_rol']    = $usuario['rol'];
        $_SESSION['usuario_email']  = $usuario['email'];

        header('Location: index.php');
        exit;
    } else {
        header('Location: login.php?error=1');
        exit;
    }
}