<?php 
include __DIR__ . '/../conexion/conexion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.3.2/sweetalert2.css">
    <title>Proyecto</title>
</head>
<body>
<?php
$mensaje = isset($_GET['msj']) ? htmlentities($_GET['msj']) : (isset($_GET['msg']) ? htmlentities($_GET['msg']) : '');
$c = isset($_GET['c']) ? htmlentities($_GET['c']) : '';
$p = isset($_GET['p']) ? htmlentities($_GET['p']) : '';
$t = isset($_GET['t']) ? htmlentities($_GET['t']) : '';

$carpetas = [
    ['home', '../inicio/'],
    ['salir', '/U3G2/'],
    ['us', '../controlador/usuarios/'],
    ['pe', '../controlador/perfil/'],
    ['cli', '../controlador/clientes/'],
    ['prod', '../controlador/productos/'],
    ['ven', '../controlador/venta/']
];

$_carpeta = '../';
for ($i = 0; $i < count($carpetas); $i++) {
    if ($c == $carpetas[$i][0]) {
        $_carpeta = $carpetas[$i][1];
        break;
    }
}

$paginas = [
    ['in', 'index.php'],
    ['home', 'index.php'],
    ['salir', ''],
    ['perfil', 'perfil.php'],
    ['img', 'imagenes.php'],
    ['can', 'cancelados.php'],
    ['sl', 'slider.php']
];

$pagina = 'index.php';
for ($i = 0; $i < count($paginas); $i++) {
    if ($p == $paginas[$i][0]) {
        $pagina = $paginas[$i][1];
        break;
    }
}

if (isset($_GET['id'])) {
    $id = htmlentities($_GET['id']);
    $dir = $_carpeta . $pagina . '?id=' . $id;
} else {
    $dir = $_carpeta . $pagina;
}

if ($t == "error") {
    $titulo = "Oppss...";
} else {
    $titulo = "Buen trabajo!";
}
?>

<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hWvnYaiADRTO2PzUGlmIJr8BLUSjGIZsDYgMIJvzb8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.3.2/sweetalert2.js"></script>
<script>
swal({
    title: '<?php echo $titulo ?>',
    text: "<?php echo $mensaje ?>",
    type: '<?php echo $t ?>',
    confirmButtonColor: '#3085d6',
    confirmButtonText: 'Ok'
}).then(function () {
    location.href = '<?php echo $dir ?>';
});

$(document).click(function() {
    location.href = '<?php echo $dir ?>';
});

$(document).keyup(function(e) {
    if (e.which == 27) {
        location.href = '<?php echo $dir ?>';
    }
});
</script>
</body>
</html>