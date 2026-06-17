<?php
include "../conexion/conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_SESSION['idUsuario'];
    $nombre = $con->real_escape_string(htmlentities($_POST['nombre']));
    $correo = $con->real_escape_string(htmlentities($_POST['correo']));
    
    // Verificar si el correo ya existe en otro usuario
    $check = $con->query("SELECT idUsuario FROM usuario WHERE correo = '$correo' AND idUsuario != $id");
    if ($check->num_rows > 0) {
        header('location:../../extend/alerta.php?msg=El correo ya está registrado por otro usuario&c=home&p=in&t=error');
        exit;
    }
    
    $up = $con->query("UPDATE usuario SET nombre = '$nombre', correo = '$correo' WHERE idUsuario = $id");
    
    if ($up) {
        $_SESSION['nombre'] = $nombre;
        $_SESSION['correo'] = $correo;
        header('location:../../extend/alerta.php?msg=Datos actualizados correctamente&c=home&p=in&t=success');
    } else {
        header('location:../../extend/alerta.php?msg=Error al actualizar&c=home&p=in&t=error');
    }
}
?>