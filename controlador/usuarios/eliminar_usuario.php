<?php include '../../conexion/conexion.php';
$id = $con->real_escape_string(htmlentities($_GET['id']));

$del = $con->query("DELETE FROM usuario WHERE idUsuario='$id' ");

if ($del) {
    header('location: /U3G1/extend/alerta.php?msg=Usuario eliminado&c=us&p=in&t=success');
} else {
    header('location: /U3G1/extend/alerta.php?msg=El usuario no pudo ser eliminado&c=us&p=in&t=error');
}

$con->close();
?>