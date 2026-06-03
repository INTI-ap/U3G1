<?php
include __DIR__ . '/../../extend/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    header('location:../../extend/alerta.php?msg=ID de usuario no válido&c=us&p=in&t=error');
    exit;
}

$sel = $con->query("SELECT * FROM usuario WHERE idUsuario = $id");
if ($sel->num_rows == 0) {
    header('location:../../extend/alerta.php?msg=Usuario no encontrado&c=us&p=in&t=error');
    exit;
}
$usuario = $sel->fetch_assoc();
?>

<div class="row">
  <div class="col s12 m8 offset-m2">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Editar Usuario</span>
        <form action="update_usuario.php" method="post" enctype="multipart/form-data" autocomplete="off">
          <input type="hidden" name="id" value="<?php echo $usuario['idUsuario'] ?>">

          <div class="input-field">
            <input type="text" name="nick" id="nick" required pattern="[A-Za-z]{8,15}" title="8-15 letras" value="<?php echo htmlspecialchars($usuario['nick']) ?>">
            <label for="nick">Nick:</label>
          </div>
          <div class="validation-nick"></div>

          <div class="input-field">
            <input type="text" name="nombre" id="nombre" required pattern="[A-Za-záéíóúñÁÉÍÓÚÑ\s]{2,50}" value="<?php echo htmlspecialchars($usuario['nombre']) ?>">
            <label for="nombre">Nombre completo:</label>
          </div>

          <div class="input-field">
            <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($usuario['correo']) ?>">
            <label for="correo">Correo electrónico:</label>
          </div>

          <div class="input-field">
            <select name="nivel" id="nivel" required>
              <option value="1" <?php echo ($usuario['nivel'] == 1) ? 'selected' : ''; ?>>USUARIO</option>
              <option value="2" <?php echo ($usuario['nivel'] == 2) ? 'selected' : ''; ?>>ADMINISTRADOR</option>
            </select>
            <label>Nivel</label>
          </div>

          <div class="input-field">
            <input type="password" name="pass1" id="pass1" pattern="[A-Za-z0-9]{8,15}" title="8-15 caracteres alfanuméricos">
            <label for="pass1">Nueva contraseña (dejar en blanco si no se cambia)</label>
          </div>

          <div class="input-field">
            <input type="password" name="pass2" id="pass2" pattern="[A-Za-z0-9]{8,15}">
            <label for="pass2">Confirmar nueva contraseña</label>
          </div>

          <div class="file-field input-field">
            <div class="btn">
              <span>Foto</span>
              <input type="file" name="foto" accept="image/png,image/jpeg">
            </div>
            <div class="file-path-wrapper">
              <input class="file-path validate" type="text" placeholder="Seleccione una nueva imagen (opcional)">
            </div>
          </div>

          <?php if (!empty($usuario['foto']) && file_exists($usuario['foto'])): ?>
            <div class="center-align">
              <img src="<?php echo $usuario['foto'] ?>" width="100" height="100" class="circle" style="object-fit: cover;">
              <p class="grey-text">Imagen actual</p>
            </div>
          <?php endif; ?>

          <div class="row">
            <div class="col s6">
              <button type="submit" class="btn blue waves-effect waves-light" id="btn_actualizar">Actualizar <i class="material-icons right">send</i></button>
            </div>
            <div class="col s6 right-align">
              <a href="index.php" class="btn grey waves-effect waves-light">Cancelar <i class="material-icons right">cancel</i></a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
  $('select').formSelect();

  // Validar nick duplicado (excluyendo el usuario actual)
  $('#nick').change(function() {
    var nick = $(this).val();
    var id = $('input[name="id"]').val();
    if (nick.length >= 8) {
      $.ajax({
        url: 'validacion_nick_edit.php',
        type: 'POST',
        data: { nick: nick, id: id },
        beforeSend: function() {
          $('.validation-nick').html('<span class="blue-text">Verificando nick...</span>');
        },
        success: function(resp) {
          $('.validation-nick').html(resp);
          if (resp.indexOf('disponible') !== -1) {
            $('#btn_actualizar').prop('disabled', false);
          } else {
            $('#btn_actualizar').prop('disabled', true);
          }
        },
        error: function() {
          $('.validation-nick').html('<span class="red-text">Error en validación</span>');
        }
      });
    } else {
      $('.validation-nick').html('<span class="orange-text">El nick debe tener al menos 8 caracteres</span>');
      $('#btn_actualizar').prop('disabled', true);
    }
  });

  // Validar coincidencia de contraseñas si se ingresa una nueva
  $('#pass2').change(function() {
    var pass1 = $('#pass1').val();
    var pass2 = $(this).val();
    if (pass1 !== '' && pass2 !== '') {
      if (pass1 === pass2) {
        swal('Bien hecho', 'Las contraseñas coinciden', 'success');
        $('#btn_actualizar').prop('disabled', false);
      } else {
        swal('Error', 'Las contraseñas no coinciden', 'error');
        $('#btn_actualizar').prop('disabled', true);
      }
    } else {
      $('#btn_actualizar').prop('disabled', false);
    }
  });
});
</script>

<?php include '../../extend/scripts.php'; ?>
</body>
</html>