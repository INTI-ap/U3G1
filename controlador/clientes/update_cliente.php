<?php
include __DIR__ . '/../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nombre = $con->real_escape_string(htmlentities($_POST['nombre']));
    $apellido = $con->real_escape_string(htmlentities($_POST['apellido']));
    $correo = $con->real_escape_string(htmlentities($_POST['correo']));
    $fechaingreso = $con->real_escape_string(htmlentities($_POST['fechaingreso']));
    $telefono = $con->real_escape_string(htmlentities($_POST['telefono']));

    // Validar que el correo no exista en otro cliente
    $check = $con->query("SELECT idCliente FROM Cliente WHERE correo = '$correo' AND idCliente != $id");
    if ($check->num_rows > 0) {
        header('location:../../extend/alerta.php?msg=El correo ya está registrado por otro cliente&c=cli&p=in&t=error');
        exit;
    }

    $up = $con->query("UPDATE Cliente SET 
                        nombre='$nombre', 
                        apellido='$apellido', 
                        correo='$correo', 
                        fechaingreso='$fechaingreso', 
                        telefono='$telefono' 
                      WHERE idCliente=$id");
    
    if ($up) {
        header('location:../../extend/alerta.php?msg=Cliente actualizado correctamente&c=cli&p=in&t=success');
    } else {
        header('location:../../extend/alerta.php?msg=Error al actualizar el cliente&c=cli&p=in&t=error');
    }
} else {
    header('location:../../extend/alerta.php?msg=Acceso no válido&c=cli&p=in&t=error');
}
$con->close();
?>