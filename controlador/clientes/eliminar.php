<?php
include '../../conexion/conexion.php';
$id = $con->real_escape_string(htmlentities($_GET['id']));
$del = $con->query("DELETE FROM Cliente WHERE idCliente='$id'");
if ($del) {
    header('location:../../extend/alerta.php?msg=Cliente eliminado&c=cli&p=in&t=success');
} else {
    header('location:../../extend/alerta.php?msg=No se pudo eliminar&c=cli&p=in&t=error');
}
$con->close();
?>