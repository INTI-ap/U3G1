<?php
include 'conexion/conexion.php';

// Si ya está logueado, redirigir al inicio
if (isset($_SESSION['nick'])) {
    header('location: inicio/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema X</title>
    <link rel="stylesheet" href="vista/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border-radius: 15px;
            overflow: hidden;
        }
        .login-header {
            background: #3f51b5;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .btn-login {
            background: #3f51b5;
            width: 100%;
        }
        .btn-login:hover {
            background: #303f9f;
        }
    </style>
</head>
<body>
    <div class="row">
        <div class="col s12 m6 l4 offset-m3 offset-l4">
            <div class="card login-card">
                <div class="login-header">
                    <i class="material-icons large">account_circle</i>
                    <h4>Sistema X</h4>
                    <p>Ingrese sus credenciales</p>
                </div>
                <div class="card-content">
                    <form id="loginForm" method="POST">
                        <div class="input-field">
                            <i class="material-icons prefix">person</i>
                            <input type="text" name="nick" id="nick" required>
                            <label for="nick">Usuario</label>
                        </div>
                        <div class="input-field">
                            <i class="material-icons prefix">lock</i>
                            <input type="password" name="pass" id="pass" required>
                            <label for="pass">Contraseña</label>
                        </div>
                        <button type="submit" class="btn btn-login waves-effect waves-light">
                            <i class="material-icons right">send</i>Ingresar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="vista/js/jquery-3.2.1.min.js"></script>
    <script src="vista/js/materialize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function(){
        $('select').formSelect();
        
        $('#loginForm').submit(function(e){
            e.preventDefault();
            
            $.ajax({
                url: 'login/validar_login.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function(){
                    Swal.fire({
                        title: 'Verificando...',
                        text: 'Por favor espere',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                },
                success: function(response){
                    if(response.success){
                        Swal.fire('Éxito', response.message, 'success').then(() => {
                            window.location = 'inicio/index.php';
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(){
                    Swal.fire('Error', 'Error en el servidor', 'error');
                }
            });
        });
    });
    </script>
</body>
</html>