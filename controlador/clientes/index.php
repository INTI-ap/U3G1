<?php include "../../extend/header.php"; ?>

<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">ALTA DE CLIENTES</span>
        <form class="form" action="ins_cliente.php" method="post" autocomplete="off">
          <!-- mismos campos -->
          <div class="input-field">
            <input type="text" name="nombre" required autofocus pattern="[A-Za-záéíóúñÁÉÍÓÚÑ\s]{2,50}" title="Solo letras y espacios" id="nombre">
            <label for="nombre">Nombre:</label>
          </div>
          <div class="input-field">
            <input type="text" name="apellido" required pattern="[A-Za-záéíóúñÁÉÍÓÚÑ\s]{2,50}" title="Solo letras y espacios" id="apellido">
            <label for="apellido">Apellido:</label>
          </div>
          <div class="input-field">
            <input type="email" name="correo" required id="correo_cliente">
            <label for="correo_cliente">Correo:</label>
            <div class="validation-correo"></div>
          </div>
          <div class="input-field">
            <input type="text" name="fechaingreso" class="datepicker" required placeholder="dd/mm/aaaa" id="fechaingreso">
            <label for="fechaingreso">Fecha ingreso:</label>
          </div>
          <div class="input-field">
            <input type="tel" name="telefono" required pattern="[0-9]{9,12}" title="9 a 12 dígitos" id="telefono">
            <label for="telefono">Teléfono:</label>
          </div>
          <button type="submit" class="btn black">Guardar <i class="material-icons">send</i></button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- barra de búsqueda igual -->
<div class="row">...</div>

<?php
$sel = $con->query("SELECT * FROM Cliente");
$total = mysqli_num_rows($sel);
?>
<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Clientes (<span id="total-clientes"><?php echo $total ?></span>)</span>
        <table class="striped responsive-table" id="tabla-clientes">
          <thead>
            <tr><th>Nombre</th><th>Apellido</th><th>Correo</th><th>Ingreso</th><th>Teléfono</th><th>Editar</th><th>Eliminar</th></tr>
          </thead>
          <tbody>
          <?php while($f = $sel->fetch_assoc()): ?>
            <tr class="cliente-row" data-id="<?php echo $f['idCliente'] ?>">
              <td><?php echo htmlspecialchars($f['nombre']) ?></td>
              <td><?php echo htmlspecialchars($f['apellido']) ?></td>
              <td><?php echo htmlspecialchars($f['correo']) ?></td>
              <td><?php echo $f['fechaingreso'] ?></td>
              <td><?php echo $f['telefono'] ?></td>
              <td><a href="edit_cliente.php?id=<?php echo $f['idCliente'] ?>" class="btn-floating btn-small blue"><i class="material-icons">edit</i></a></td>
              <td><a href="#" class="btn-floating btn-small red" onclick="swal({ title: '¿Eliminar cliente?', text: 'No podrás recuperarlo', type: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Sí, eliminar' }).then(function(){ location.href='eliminar.php?id=<?php echo $f['idCliente'] ?>'; })"><i class="material-icons">clear</i></a></td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function(){
  $('.datepicker').datepicker({ format: 'dd/mm/yyyy' });
  // Validación de correo para nuevo cliente
  $('#correo_cliente').change(function(){
    var correo = $(this).val();
    if(correo){
      $.ajax({
        url: 'validacion_correo.php',
        type: 'POST',
        data: { correo: correo },
        success: function(res){ $('.validation-correo').html(res); }
      });
    }
  });
});
</script>

<?php include '../../extend/scripts.php'; ?>
</body>
</html>