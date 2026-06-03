<?php
include __DIR__ . '/../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nick = $con->real_escape_string(htmlentities($_POST['nick']));
    $pass1 = $con->real_escape_string(htmlentities($_POST['pass1']));
    $nivel = $con->real_escape_string(htmlentities($_POST['nivel']));
    $nombre = $con->real_escape_string(htmlentities($_POST['nombre']));
    $correo = $con->real_escape_string(htmlentities($_POST['correo']));
    
    if (empty($nick) || empty($pass1) || empty($nivel) || empty($nombre)) {
        header('location:/U3G1/extend/alerta.php?msg=Hay un campo sin especificar&c=us&p=in&t=error');
        exit;
    }
    if (!ctype_alnum($nick)) {
        header('location:/U3G1/extend/alerta.php?msg=El nick debe contener solo letras y numeros&c=us&p=in&t=error');
        exit;
    }
    if (!ctype_digit($nivel)) {
        header('location:/U3G1/extend/alerta.php?msg=El nivel solo debe contener numeros&c=us&p=in&t=error');
        exit;
    }
    if (!ctype_alpha(str_replace(' ', '', $nombre))) {
        header('location:/U3G1/extend/alerta.php?msg=El nombre solo debe contener letras&c=us&p=in&t=error');
        exit;
    }

    $usuario = strlen($nick);
    $contra = strlen($pass1);
    if ($usuario < 8 || $usuario > 15) {
        header('location:/U3G1/extend/alerta.php?msg=El nick debe contener entre 8 y 15 caracteres&c=us&p=in&t=error');
        exit;
    }
    if ($contra < 8 || $contra > 15) {
        header('location:/U3G1/extend/alerta.php?msg=La contraseña debe contener entre 8 y 15 caracteres&c=us&p=in&t=error');
        exit;
    }
    if (!empty($correo)) {
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            header('location:/U3G1/extend/alerta.php?msg=El email no es valido&c=us&p=in&t=error');
            exit;
        }
    }
    
    $extension = '';
    $ruta = 'foto_perfil';
    $archivo = $_FILES['foto']['tmp_name'];
    $nombrearchivo = $_FILES['foto']['name'];
    $info = pathinfo($nombrearchivo);
    
    if ($archivo != "") {
        $extension = $info['extension'];
        if ($extension == "png" || $extension == "PNG" || $extension == "jpg" || $extension == "JPG") {
            if (!file_exists('foto_perfil')) {
                mkdir('foto_perfil', 0777, true);
            }
            move_uploaded_file($archivo, 'foto_perfil/' . $nick . '.' . $extension);
            $ruta = "foto_perfil/" . $nick . '.' . $extension;
        } else {
            header('location:/U3G1/extend/alerta.php?msg=El formato no es valido (solo PNG y JPG)&c=us&p=in&t=error');
            exit;
        }
    } else {
        $ruta = "foto_perfil/perfil.png";
    }
    
    $pass1 = sha1($pass1);
    $ins = $con->query("INSERT INTO usuario VALUES('','$nick','$nombre','$pass1','$correo','$nivel',1,'$ruta')");
    
    if ($ins) {
        header('location:/U3G1/extend/alerta.php?msg=El usuario ha sido registrado&c=us&p=in&t=success');
    } else {
        header('location:/U3G1/extend/alerta.php?msg=El usuario no pudo ser registrado&c=us&p=in&t=error');
    }
    
    $con->close();
} else {
    header('location:/U3G1/extend/alerta.php?msg=Utiliza el formulario&c=us&p=in&t=error');
}
?>