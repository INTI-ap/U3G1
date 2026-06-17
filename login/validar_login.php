<?php
include '../conexion/conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nick = trim($_POST['nick']);
    $pass = trim($_POST['pass']);
    
    if (empty($nick) || empty($pass)) {
        echo json_encode(['success' => false, 'message' => 'Ingrese usuario y contraseña']);
        exit;
    }
    
    // Buscar usuario
    $stmt = $con->prepare("SELECT idUsuario, nick, nombre, pass, correo, nivel, bloqueo, foto FROM usuario WHERE nick = ?");
    $stmt->bind_param("s", $nick);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        exit;
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Verificar contraseña (SHA1 como en ins_usuario.php)
    $pass_hashed = sha1($pass);
    
    if ($user['pass'] != $pass_hashed) {
        echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
        exit;
    }
    
    // Verificar bloqueo
    if ($user['bloqueo'] == 0) {
        echo json_encode(['success' => false, 'message' => 'Usuario bloqueado. Contacte al administrador']);
        exit;
    }
    
    // Iniciar sesión
    $_SESSION['idUsuario'] = $user['idUsuario'];
    $_SESSION['nick'] = $user['nick'];
    $_SESSION['nombre'] = $user['nombre'];
    $_SESSION['correo'] = $user['correo'];
    $_SESSION['nivel'] = $user['nivel'];
    $_SESSION['foto'] = $user['foto'];
    
    // Determinar nivel
    $nivelTexto = ($user['nivel'] == 1) ? 'USUARIO' : (($user['nivel'] == 2) ? 'ADMINISTRADOR' : 'VENDEDOR');
    $_SESSION['nivel_texto'] = $nivelTexto;
    
    echo json_encode(['success' => true, 'message' => 'Bienvenido ' . $user['nombre']]);
    
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>