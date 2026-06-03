<?php
include '../../conexion/conexion.php';
$correo = $con->real_escape_string($_POST['correo']);
$sel = $con->query("SELECT idCliente FROM Cliente WHERE correo='$correo'");
if ($sel->num_rows > 0) {
    echo '<label style="color:red;">El correo ya existe</label>';
} else {
    echo '<label style="color:green;">Correo disponible</label>';
}
$con->close();
?>