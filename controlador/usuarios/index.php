<?php include "../../extend/header.php"; ?>

<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">ALTA DE USUARIOS</span>
        <form class="form" action="ins_usuario.php" method="post" enctype="multipart/form-data" autocomplete="off">
          <div class="input-field">
            <input type="text" name="nick" required autofocus title="8-15 letras" pattern="[A-Za-z]{8,15}" id="nick">
            <label for="nick">Nick:</label>
          </div>
          <div class="validation"></div>

          <div class="input-field">
            <input type="password" name="pass1" title="8-15 caracteres alfanuméricos" pattern="[A-Za-z0-9]{8,15}" id="pass1" required>
            <label for="pass1">Contraseña:</label>
          </div>

          <div class="input-field">
            <input type="password" title="Confirmar contraseña" pattern="[A-Za-z0-9]{8,15}" id="pass2" required>
            <label for="pass2">Verificar contraseña:</label>
          </div>

          <div class="input-field">
            <select name="nivel" class="browser-default" required>
              <option value="1">USUARIO</option>
              <option value="2">ADMINISTRADOR</option>
            </select>
          </div>

          <div class="input-field">
            <input type="text" name="nombre" title="Solo letras y espacios" id="nombre" required pattern="[A-Za-záéíóúñÁÉÍÓÚÑ\s]{2,50}">
            <label for="nombre">Nombre completo:</label>
          </div>

          <div class="input-field">
            <input type="email" name="correo" title="Correo electrónico" id="correo">
            <label for="correo">Correo electrónico:</label>
          </div>

          <div class="file-field input-field">
            <div class="btn">
              <span>Foto</span>
              <input type="file" name="foto" accept="image/png,image/jpeg">
            </div>
            <div class="file-path-wrapper">
              <input class="file-path validate" type="text" placeholder="Seleccione una imagen">
            </div>
          </div>

          <button type="submit" class="btn black" id="btn_guardar">Guardar <i class="material-icons">send</i></button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Barra de búsqueda -->
<div class="row">
  <div class="col s12">
    <nav class="brown lighten-3">
      <div class="nav-wrapper">
        <div class="input-field">
          <input type="search" id="buscarU" autocomplete="off" placeholder="Buscar por nick, nombre o correo...">
          <label for="buscarU"><i class="material-icons">search</i></label>
          <i class="material-icons" id="clear-searchU" style="cursor: pointer;">close</i>
        </div>
      </div>
    </nav>
  </div>
</div>

<?php 
$sel = $con->query("SELECT * FROM usuario");
$row = mysqli_num_rows($sel);
?>
<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Usuarios (<span id="total-usuarios"><?php echo $row ?></span>) 
          <span id="resultado-busquedaU" style="font-size: 14px; color: #666;"></span>
        </span>
        <div class="responsive-table">
          <table class="striped" id="tabla-usuarios">
            <thead>
              <tr>
                <th>Nick</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Nivel</th>
                <th>Editar</th>
                <th>Foto</th>
                <th>Bloqueo</th>
                <th>Eliminar</th>
              </tr>
            </thead>
            <tbody>
            <?php while($f = $sel->fetch_assoc()): ?>
              <tr class="usuario-row" data-id="<?php echo $f['idUsuario'] ?>">
                <td><?php echo htmlspecialchars($f['nick']) ?></td>
                <td><?php echo htmlspecialchars($f['nombre']) ?></td>
                <td><?php echo htmlspecialchars($f['correo']) ?></td>
                <td><?php echo ($f['nivel'] == 1) ? 'USUARIO' : 'ADMINISTRADOR' ?></td>
                <!-- Botón Editar que redirige a edit_usuario.php -->
                <td><a href="edit_usuario.php?id=<?php echo $f['idUsuario'] ?>" class="btn-floating btn-small blue"><i class="material-icons">edit</i></a></td>
                <td><img src="<?php echo $f['foto'] ?>" width="50" height="50" class="circle" style="object-fit: cover;"></td>
                <td>
                  <?php if ($f['bloqueo'] == 1): ?>
                    <a href="bloqueo_manual.php?us=<?php echo $f['idUsuario'] ?>&bl=<?php echo $f['bloqueo'] ?>" class="btn-floating btn-small green">
                      <i class="material-icons">lock_open</i>
                    </a>
                  <?php else: ?>
                    <a href="bloqueo_manual.php?us=<?php echo $f['idUsuario'] ?>&bl=<?php echo $f['bloqueo'] ?>" class="btn-floating btn-small red">
                      <i class="material-icons">lock_outline</i>
                    </a>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="#" class="btn-floating btn-small red" 
                  onclick="swal({ title: '¿Está seguro que desea eliminar al usuario?', 
                  text: '¡Al eliminarlo no podrá recuperarlo!', 
                  type: 'warning', showCancelButton: true, 
                  confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', 
                  confirmButtonText: 'Sí, eliminarlo!' }).then(function () 
                  { location.href='eliminar_usuario.php?id=<?php echo $f['idUsuario'] ?>'; })">
                    <i class="material-icons">clear</i>
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
  // Validación de nick duplicado
  $('#nick').change(function() {
    var nick = $(this).val();
    if(nick.length >= 8) {
      $.ajax({
        url: 'validacion_nick.php',
        type: 'POST',
        data: { nick: nick },
        beforeSend: function() {
          $('.validation').html('<span class="blue-text">Verificando nick...</span>');
        },
        success: function(resp) {
          $('.validation').html(resp);
          if(resp.indexOf('disponible') !== -1) {
            $('#btn_guardar').show();
          } else {
            $('#btn_guardar').hide();
          }
        },
        error: function() {
          $('.validation').html('<span class="red-text">Error en validación</span>');
        }
      });
    } else {
      $('.validation').html('<span class="orange-text">El nick debe tener al menos 8 caracteres</span>');
      $('#btn_guardar').hide();
    }
  });

  // Validación de contraseñas
  $('#btn_guardar').hide();
  $('#pass2').change(function() {
    if ($('#pass1').val() == $('#pass2').val()) {
      swal('Bien hecho', 'Las contraseñas coinciden', 'success');
      $('#btn_guardar').show();
    } else {
      swal('Error', 'Las contraseñas no coinciden', 'error');
      $('#btn_guardar').hide();
    }
  });

  // Prevenir envío con Enter
  $('.form').keypress(function(e) {
    if (e.which == 13) return false;
  });
});

// Función para convertir texto a mayúsculas
function may(valor, id) {
  if (id === 'nombre') {
    document.getElementById(id).value = valor.toUpperCase();
  }
}
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.3.2/sweetalert2.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.3.2/sweetalert2.js"></script>

<?php include '../../extend/scripts.php'; ?>
</body>
</html>