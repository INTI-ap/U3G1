<?php
include __DIR__ . '/../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nick = $con->real_escape_string(htmlentities($_POST['nick']));
    $nombre = $con->real_escape_string(htmlentities($_POST['nombre']));
    $correo = $con->real_escape_string(htmlentities($_POST['correo']));
    $nivel = $con->real_escape_string(htmlentities($_POST['nivel']));
    $pass1 = trim($_POST['pass1']);
    
    // Validar nick existente (excluyendo este usuario)
    $checkNick = $con->query("SELECT idUsuario FROM usuario WHERE nick = '$nick' AND idUsuario != $id");
    if ($checkNick->num_rows > 0) {
        header('location:../../extend/alerta.php?msg=El nick ya está en uso&c=us&p=in&t=error');
        exit;
    }
    
    // Validar correo existente (excluyendo este usuario) si se proporcionó
    if (!empty($correo)) {
        $checkCorreo = $con->query("SELECT idUsuario FROM usuario WHERE correo = '$correo' AND idUsuario != $id");
        if ($checkCorreo->num_rows > 0) {
            header('location:../../extend/alerta.php?msg=El correo ya está registrado por otro usuario&c=us&p=in&t=error');
            exit;
        }
    }
    
    // Procesar foto
    $ruta = $con->query("SELECT foto FROM usuario WHERE idUsuario = $id")->fetch_assoc()['foto'];
    if ($_FILES['foto']['tmp_name'] != "") {
        $archivo = $_FILES['foto']['tmp_name'];
        $nombrearchivo = $_FILES['foto']['name'];
        $info = pathinfo($nombrearchivo);
        $extension = strtolower($info['extension']);
        if ($extension == "png" || $extension == "jpg" || $extension == "jpeg") {
            $directorio = 'foto_perfil/';
            if (!file_exists($directorio)) {
                mkdir($directorio, 0777, true);
            }
            $nuevoNombre = $nick . '_' . time() . '.' . $extension;
            move_uploaded_file($archivo, $directorio . $nuevoNombre);
            $ruta = $directorio . $nuevoNombre;
        } else {
            header('location:../../extend/alerta.php?msg=Formato de imagen no válido (solo PNG, JPG)&c=us&p=in&t=error');
            exit;
        }
    }
    
    // Construir consulta UPDATE (con o sin contraseña)
    if (!empty($pass1)) {
        $pass1 = sha1($pass1);
        $up = $con->query("UPDATE usuario SET nick='$nick', nombre='$nombre', correo='$correo', nivel='$nivel', pass='$pass1', foto='$ruta' WHERE idUsuario=$id");
    } else {
        $up = $con->query("UPDATE usuario SET nick='$nick', nombre='$nombre', correo='$correo', nivel='$nivel', foto='$ruta' WHERE idUsuario=$id");
    }
    
    if ($up) {
        header('location:../../extend/alerta.php?msg=Usuario actualizado correctamente&c=us&p=in&t=success');
    } else {
        header('location:../../extend/alerta.php?msg=Error al actualizar el usuario&c=us&p=in&t=error');
    }
} else {
    header('location:../../extend/alerta.php?msg=Acceso no válido&c=us&p=in&t=error');
}
$con->close();
?>