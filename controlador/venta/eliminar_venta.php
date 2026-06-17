<?php
include '../../conexion/conexion.php';
$id = intval($_GET['id']);
// Primero eliminar detalles (o automático si tiene CASCADE)
$con->query("DELETE FROM DetalleVenta WHERE Venta_idVenta=$id");
$del = $con->query("DELETE FROM Venta WHERE idVenta=$id");
if ($del) header('location:../../extend/alerta.php?msg=Venta eliminada&c=ve&p=in&t=success');
else header('location:../../extend/alerta.php?msg=Error&c=ve&p=in&t=error');
$con->close();
?>