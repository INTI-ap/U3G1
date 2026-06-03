<?php
include __DIR__ . '/../../extend/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    header('location:../../extend/alerta.php?msg=ID de cliente no válido&c=cli&p=in&t=error');
    exit;
}

$sel = $con->query("SELECT * FROM Cliente WHERE idCliente = $id");
if ($sel->num_rows == 0) {
    header('location:../../extend/alerta.php?msg=Cliente no encontrado&c=cli&p=in&t=error');
    exit;
}
$cliente = $sel->fetch_assoc();
?>

<div class="row">
  <div class="col s12 m8 offset-m2">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Editar Cliente</span>
        <form action="update_cliente.php" method="post" autocomplete="off">
          <input type="hidden" name="id" value="<?php echo $cliente['idCliente'] ?>">

          <div class="input-field">
            <input type="text" name="nombre" id="nombre" required pattern="[A-Za-záéíóúñÁÉÍÓÚÑ\s]{2,50}" value="<?php echo htmlspecialchars($cliente['nombre']) ?>">
            <label for="nombre">Nombre:</label>
          </div>

          <div class="input-field">
            <input type="text" name="apellido" id="apellido" required pattern="[A-Za-záéíóúñÁÉÍÓÚÑ\s]{2,50}" value="<?php echo htmlspecialchars($cliente['apellido']) ?>">
            <label for="apellido">Apellido:</label>
          </div>

          <div class="input-field">
            <input type="email" name="correo" id="correo" required value="<?php echo htmlspecialchars($cliente['correo']) ?>">
            <label for="correo">Correo:</label>
            <div class="edit-validation-correo" style="font-size:12px;"></div>
          </div>

          <div class="input-field">
            <input type="text" name="fechaingreso" id="fechaingreso" class="datepicker" required value="<?php echo $cliente['fechaingreso'] ?>">
            <label for="fechaingreso">Fecha ingreso:</label>
          </div>

          <div class="input-field">
            <input type="tel" name="telefono" id="telefono" required pattern="[0-9]{9,12}" value="<?php echo $cliente['telefono'] ?>">
            <label for="telefono">Teléfono:</label>
          </div>

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
  $('.datepicker').datepicker({ format: 'dd/mm/yyyy' });

  // Validar correo duplicado en edición (excluyendo el ID actual)
  $('#correo').change(function() {
    var correo = $(this).val();
    var id = $('input[name="id"]').val();
    if (correo) {
      $.ajax({
        url: 'validacion_correo_edit.php',
        type: 'POST',
        data: { correo: correo, id: id },
        success: function(resp) {
          $('.edit-validation-correo').html(resp);
          if (resp.indexOf('disponible') !== -1) {
            $('#btn_actualizar').prop('disabled', false);
          } else {
            $('#btn_actualizar').prop('disabled', true);
          }
        },
        error: function() {
          $('.edit-validation-correo').html('<span class="red-text">Error en validación</span>');
        }
      });
    } else {
      $('.edit-validation-correo').html('');
      $('#btn_actualizar').prop('disabled', false);
    }
  });
});
</script>

<?php include '../../extend/scripts.php'; ?>
</body>
</html>