<?php
include __DIR__ . '/../../conexion/conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $con->real_escape_string(htmlentities($_POST['nombre']));
    $estado = $con->real_escape_string(htmlentities($_POST['estado']));
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $ins = $con->query("INSERT INTO Producto VALUES (NULL,'$nombre','$estado',$precio,$stock)");
    if ($ins) header('location:../../extend/alerta.php?msg=Producto agregado&c=prod&p=in&t=success');
    else header('location:../../extend/alerta.php?msg=Error&c=prod&p=in&t=error');
} else header('location:../../extend/alerta.php?msg=Formulario no válido&c=prod&p=in&t=error');
$con->close();
?>