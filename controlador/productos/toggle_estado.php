<?php
include '../../conexion/conexion.php';
$id = intval($_GET['id']);
$current = $con->query("SELECT estado FROM Producto WHERE idProducto=$id")->fetch_assoc()['estado'];
$new = ($current == 'activo') ? 'inactivo' : 'activo';
$up = $con->query("UPDATE Producto SET estado='$new' WHERE idProducto=$id");
if ($up) header('location:index.php');
else header('location:../../extend/alerta.php?msg=Error al cambiar estado&c=prod&p=in&t=error');
$con->close();
?>