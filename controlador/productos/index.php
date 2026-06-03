<?php include "../../extend/header.php"; ?>

<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">ALTA DE PRODUCTOS</span>
        <form class="form" action="ins_producto.php" method="post" autocomplete="off">
          <div class="input-field">
            <input type="text" name="nombre" required autofocus
                   pattern="[A-Za-záéíóúñÁÉÍÓÚÑ0-9\s]{3,100}"
                   title="3 a 100 caracteres (letras, números, espacios, tildes, ñ)"
                   id="nombre_producto">
            <label for="nombre_producto">Nombre del producto</label>
          </div>
          <div class="validation"></div>

          <div class="input-field">
            <select name="estado" class="browser-default" id="estado_producto" required>
              <option value="activo">Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>

          <div class="input-field">
            <input type="number" step="0.01" name="precio" required min="0" id="precio_producto">
            <label for="precio_producto">Precio</label>
          </div>

          <div class="input-field">
            <input type="number" name="stock" required min="0" id="stock_producto">
            <label for="stock_producto">Stock</label>
          </div>

          <button type="submit" class="btn black" id="btn_guardar_producto">Guardar <i class="material-icons">send</i></button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col s12">
    <nav class="brown lighten-3">
      <div class="nav-wrapper">
        <div class="input-field">
          <input type="search" id="buscarP" placeholder="Buscar por nombre...">
          <label><i class="material-icons">search</i></label>
        </div>
      </div>
    </nav>
  </div>
</div>

<?php 
$sel = $con->query("SELECT * FROM Producto");
$total = $sel->num_rows;
?>
<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Productos (<span id="total-productos"><?php echo $total ?></span>)</span>
        <table class="striped responsive-table" id="tabla-productos">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Estado</th>
              <th>Precio</th>
              <th>Stock</th>
              <th>Cambiar estado</th>
              <th>Editar</th>
              <th>Eliminar</th>
            </tr>
          </thead>
          <tbody>
          <?php while($f = $sel->fetch_assoc()): ?>
            <tr class="producto-row" data-id="<?php echo $f['idProducto'] ?>"
                data-nombre="<?php echo htmlspecialchars($f['nombre']) ?>"
                data-estado="<?php echo $f['estado'] ?>"
                data-precio="<?php echo $f['precio'] ?>"
                data-stock="<?php echo $f['stock'] ?>">
              <td><?php echo htmlspecialchars($f['nombre']) ?></td>
              <td><?php echo ucfirst($f['estado']) ?></td>
              <td>$<?php echo number_format($f['precio'],2) ?></td>
              <td><?php echo $f['stock'] ?></td>
              <td>
                <a href="toggle_estado.php?id=<?php echo $f['idProducto'] ?>" 
                   class="btn-floating btn-small <?php echo ($f['estado']=='activo')?'orange':'green' ?>">
                  <i class="material-icons"><?php echo ($f['estado']=='activo')?'lock_outline':'lock_open' ?></i>
                </a>
               </td>
              <td>
                <a href="#" class="btn-floating blue modal-trigger" data-target="modal-up">
                  <i class="material-icons">edit</i>
                </a>
               </td>
              <td>
                <a href="#" class="btn-floating red" 
                   onclick="swal({ title: '¿Eliminar?', text: 'Se perderán las ventas asociadas', type: 'warning', showCancelButton: true, confirmButtonText: 'Sí, eliminar' }).then(function(){ location.href='eliminar_producto.php?id=<?php echo $f['idProducto'] ?>'; })">
                  <i class="material-icons">clear</i>
                </a>
               </td>
                          </>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal de edición (CORREGIDO) -->
<div id="modal-up" class="modal">
  <div class="modal-content">
    <h4>Editar Producto</h4>
    <form action="up_producto.php" method="post" autocomplete="off">
      <input type="hidden" name="id" id="up_id">
      <div class="input-field">
        <input type="text" name="nombre" id="up_nombre" required pattern="[A-Za-záéíóúñÁÉÍÓÚÑ0-9\s]{3,100}">
        <label for="up_nombre">Nombre</label>
      </div>
      <div class="input-field">
        <select name="estado" id="up_estado" required>
          <option value="activo">Activo</option>
          <option value="inactivo">Inactivo</option>
        </select>
        <label>Estado</label>
      </div>
      <div class="input-field">
        <input type="number" step="0.01" name="precio" id="up_precio" required min="0">
        <label for="up_precio">Precio</label>
      </div>
      <div class="input-field">
        <input type="number" name="stock" id="up_stock" required min="0">
        <label for="up_stock">Stock</label>
      </div>
      <button type="submit" class="btn blue">Actualizar</button>
    </form>
  </div>
</div>

<script>
$(document).ready(function(){
  $('select').formSelect();
  $('.modal').modal();

  $('#nombre_producto').on('change', function() {
    var nombre = $(this).val();
    if (nombre.length >= 3) {
      $.ajax({
        url: 'validacion_producto.php',
        type: 'POST',
        data: { nombre: nombre },
        beforeSend: function() {
          $('.validation-producto').html('<span class="blue-text">Verificando producto...</span>');
        },
        success: function(respuesta) {
          $('.validation-producto').html(respuesta);
          if (respuesta.indexOf('disponible') !== -1) {
            $('#btn_guardar_producto').show();
          } else {
            $('#btn_guardar_producto').hide();
          }
        },
        error: function() {
          $('.validation-producto').html('<span class="red-text">Error en la validación</span>');
        }
      });
    } else {
      $('.validation-producto').html('<span class="orange-text">El nombre debe tener al menos 3 caracteres</span>');
      $('#btn_guardar_producto').hide();
    }
  });

  // === CARGAR DATOS EN EL MODAL DE EDICIÓN ===
  $(document).on('click', '.modal-trigger', function() {
    var row = $(this).closest('.producto-row');
    $('#up_id').val(row.data('id'));
    $('#up_nombre').val(row.data('nombre'));
    $('#up_estado').val(row.data('estado'));
    $('#up_precio').val(row.data('precio'));
    $('#up_stock').val(row.data('stock'));
    
    $('#up_estado').formSelect();
    
    M.updateTextFields();
  });
});

</script>

<?php include '../../extend/scripts.php'; ?>
</body>
</html>