<?php 
    include "./conexion/conexion.php";
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./vista/css/materialize.css">
    <link type="text/css" rel="stylesheet" href="./css/materialize.min.css" media="screen,projection"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body class="card-content #01579b light-blue darken-4">
    <main>
        <div class="row">
            <div class="input-field col s12 center">
                <img src="./vista/img/logo.fw.png" width="200" class="circle">
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col s12">
                    <div class="card z-depth-5">
                        <div class="card-content #90caf9 blue lighten-3">
                            
                            <h1>
                                <span class="card-title">
                                    <center>
                                        <a href="" class="white-text">
                                            LOGIN DE ACCESO CARRITO DE COMPRAS
                                        </a>
                                    </center>
                                </span>
                            </h1>

                            <span class="card-title">
                                <center>
                                    <a href="" class="black-text">Inicio de Sesión</a>
                                </center>
                            </span>

                            <div class="input-field">
                                <i class="material-icons prefix">perm_identity</i>
                                <input type="text" name="usuario" id="usuario" required pattern="[A-Za-z]{8,15}" autofocus>
                                <label for="usuario">Usuario</label>
                            </div>

                            <div class="input-field">
                                <i class="material-icons prefix">vpn_key</i>
                                <input type="password" name="contra" id="contra" required pattern="[A-Za-z0-9]{8,15}">
                                <label for="contra">Contraseña</label>
                            </div>

                            <div class="input-field">
                                <center>
                                    <a href="#" class="link">¿Olvidó su contraseña?</a>
                                </center>
                            </div>

                            <div class="input-field center">
                                <button id="boton" class="btn black">Acceder</button>
                            </div>

                        </div>
                    </div>
                </div>	
            </div>
        </div>

<?php include './extend/scripts.php'; ?>    