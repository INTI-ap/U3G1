<?php 
include __DIR__ . "/../conexion/conexion.php";

// Verificar sesión
if (!isset($_SESSION['nick'])) {
    header('location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
   	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="/U3G2/vista/css/materialize.css">
	<link rel="stylesheet" type="text/css" href="/U3G2/vista/css/materialize.min.css">
	<link rel="stylesheet" type="text/css" href="/U3G2/vista/css/estilo.css">
	<link rel="stylesheet" type="text/css" href="/U3G2/vista/css/estilo2.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<title>SistemaX</title>
</head>
<body>
<main>	
<?php
$nivel = $_SESSION['nivel'];
if ($nivel == 2) {
    include 'menu_admin.php';
} elseif ($nivel == 1) {
    include 'menu_user.php';
} else {
    include 'menu_seller
	.php';
}
?>

