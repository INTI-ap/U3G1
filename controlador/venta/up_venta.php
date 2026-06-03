<?php
include __DIR__ . '/../../conexion/conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nombre = $con->real_escape_string(htmlentities($_POST['nombre']));
    $estado = $con->real_escape_string(htmlentities($_POST['estado']));
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $up = $con->query("UPDATE Producto SET nombre='$nombre', estado='$estado', precio=$precio, stock=$stock WHERE idProducto=$id");
    if ($up) header('location:../../extend/alerta.php?msg=Producto actualizado&c=prod&p=in&t=success');
    else header('location:../../extend/alerta.php?msg=Error&c=prod&p=in&t=error');
} else header('location:../../extend/alerta.php?msg=Acceso no válido&c=prod&p=in&t=error');
$con->close();
?>