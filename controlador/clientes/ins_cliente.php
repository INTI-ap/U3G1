<?php
include __DIR__ . '/../../conexion/conexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $con->real_escape_string(htmlentities($_POST['nombre']));
    $apellido = $con->real_escape_string(htmlentities($_POST['apellido']));
    $correo = $con->real_escape_string(htmlentities($_POST['correo']));
    $fechaingreso = $con->real_escape_string(htmlentities($_POST['fechaingreso']));
    $telefono = $con->real_escape_string(htmlentities($_POST['telefono']));

    if (empty($nombre) || empty($apellido) || empty($correo) || empty($fechaingreso) || empty($telefono)) {
        header('location:../../extend/alerta.php?msg=Todos los campos son obligatorios&c=cli&p=in&t=error');
        exit;
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        header('location:../../extend/alerta.php?msg=Correo no válido&c=cli&p=in&t=error');
        exit;
    }
    $check = $con->query("SELECT idCliente FROM Cliente WHERE correo='$correo'");
    if ($check->num_rows > 0) {
        header('location:../../extend/alerta.php?msg=El correo ya está registrado&c=cli&p=in&t=error');
        exit;
    }
    $ins = $con->query("INSERT INTO Cliente VALUES (NULL,'$nombre','$apellido','$correo','$fechaingreso','$telefono')");
    if ($ins) {
        header('location:../../extend/alerta.php?msg=Cliente registrado correctamente&c=cli&p=in&t=success');
    } else {
        header('location:../../extend/alerta.php?msg=Error al registrar&c=cli&p=in&t=error');
    }
} else {
    header('location:../../extend/alerta.php?msg=Utiliza el formulario&c=cli&p=in&t=error');
}
$con->close();
?>