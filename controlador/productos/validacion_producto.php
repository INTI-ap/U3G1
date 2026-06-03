<?php
include __DIR__ . '/../../conexion/conexion.php';

$nombre = $con->real_escape_string(trim($_POST['nombre']));

$sel = $con->query("SELECT idProducto FROM Producto WHERE nombre = '$nombre'");
$row = mysqli_num_rows($sel);

if ($row != 0) {
    echo '<label style="color:red;">El producto ya existe</label>';
} else {
    echo '<label style="color:green;">Producto disponible</label>';
}

$con->close();
?>