<?php
include '../../conexion/conexion.php';
$id = intval($_GET['id']);
// Primero eliminar detalles de ventas (por FK, pero si no hay CASCADE se hace manual)
$con->query("DELETE FROM DetalleVenta WHERE Producto_idProducto=$id");
$del = $con->query("DELETE FROM Producto WHERE idProducto=$id");
if ($del) header('location:../../extend/alerta.php?msg=Producto eliminado&c=prod&p=in&t=success');
else header('location:../../extend/alerta.php?msg=No se pudo eliminar&c=prod&p=in&t=error');
$con->close();
?>