<?php include "../../extend/header.php"; ?>
<div class="row">
  <div class="col s12 l8 offset-l2">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Nueva Venta</span>
        <form id="formVenta" action="ins_venta.php" method="post">
          <!-- Fecha -->
          <div class="input-field">
            <input type="text" name="fechaVenta" id="fechaVenta" class="datepicker" required>
            <label for="fechaVenta">Fecha</label>
          </div>

          <!-- Cliente -->
          <div class="input-field">
            <select name="cliente" id="cliente" class="browser-default" required>
              <option value="" disabled selected>Seleccione cliente</option>
              <?php 
              $cli = $con->query("SELECT idCliente, nombre, apellido FROM Cliente ORDER BY nombre"); 
              while($c = $cli->fetch_assoc()): 
              ?>
                <option value="<?php echo $c['idCliente'] ?>"><?php echo $c['nombre'] . " " . $c['apellido'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <!-- Descripción -->
          <div class="input-field">
            <input type="text" name="descripcion" id="descripcion" required maxlength="200">
            <label>Descripción</label>
          </div>

          <h5>Productos</h5>
          <div id="productos-container">
            <div class="producto-row card-panel grey lighten-4" style="padding: 10px; margin-bottom: 10px;">
              <div class="row" style="margin-bottom: 0;">
                <div class="col s12 m5">
                  <select name="producto[]" class="producto-select browser-default" style="width:100%; margin-bottom: 10px;" required>
                    <option value="" disabled selected>-- Producto --</option>
                    <?php 
                    $prod = $con->query("SELECT idProducto, nombre, precio, stock FROM Producto WHERE estado = 'activo' AND stock > 0 ORDER BY nombre"); 
                    while($p = $prod->fetch_assoc()): 
                    ?>
                      <option value="<?php echo $p['idProducto'] ?>" data-precio="<?php echo $p['precio'] ?>" data-stock="<?php echo $p['stock'] ?>">
                        <?php echo htmlspecialchars($p['nombre']) ?> - $<?php echo number_format($p['precio'],2) ?> (stock: <?php echo $p['stock'] ?>)
                      </option>
                    <?php endwhile; ?>
                  </select>
                </div>
                <div class="col s6 m3">
                  <input type="number" name="cantidad[]" class="cantidad" placeholder="Cantidad" min="1" value="1" required style="margin-bottom: 10px;">
                </div>
                <div class="col s5 m3">
                  <input type="number" step="0.01" name="descuento[]" class="descuento" placeholder="Descuento $" value="0" min="0" style="margin-bottom: 10px;">
                </div>
                <div class="col s1 m1">
                  <button type="button" class="btn-floating red btn-eliminar-producto"><i class="material-icons">delete</i></button>
                </div>
              </div>
              <div class="row">
                <div class="col s12">
                  <small class="grey-text">Subtotal producto: $<span class="subtotal-producto">0.00</span></small>
                  <small class="grey-text" style="margin-left: 15px;">Stock disponible: <span class="stock-disponible">0</span></small>
                </div>
              </div>
            </div>
          </div>

          <div class="center-align" style="margin: 15px 0;">
            <button type="button" id="agregar-producto" class="btn green"><i class="material-icons left">add</i>Agregar producto</button>
          </div>

          <!-- Resumen responsivo -->
          <div class="card-panel blue lighten-4" style="margin-top: 20px;">
            <div class="row">
              <div class="col s12 m4">
                <h6>Subtotal</h6>
                <h4>$<span id="subtotal">0.00</span></h4>
              </div>
              <div class="col s12 m4">
                <h6>Descuentos</h6>
                <h4 class="red-text">$<span id="descuento-total">0.00</span></h4>
              </div>
              <div class="col s12 m4">
                <h6><strong>Total a pagar</strong></h6>
                <h3 class="green-text">$<span id="total-pagar">0.00</span></h3>
              </div>
            </div>
          </div>

          <button type="submit" class="btn blue waves-effect waves-light" id="btn_registrar" disabled>
            <i class="material-icons left">attach_money</i>Registrar Venta
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Listado de ventas responsivo -->
<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Ventas realizadas</span>
        <div class="responsive-table">
          <table class="striped">
            <thead>
              <tr><th>ID</th><th>Fecha</th><th>Cliente</th><th>Descripción</th><th>Total</th><th>Detalle</th><th>Eliminar</th></tr>
            </thead>
            <tbody>
            <?php
            $ventas = $con->query("SELECT V.idVenta, V.fechaVenta, V.descripcion, C.nombre, C.apellido, 
            SUM(D.cantidad * P.precio - D.descuento) as total FROM Venta V 
            JOIN Cliente C ON V.Cliente_idCliente = C.idCliente 
            JOIN DetalleVenta D ON V.idVenta = D.Venta_idVenta 
            JOIN Producto P ON D.Producto_idProducto = P.idProducto 
            GROUP BY V.idVenta ORDER BY V.idVenta DESC");
            if($ventas->num_rows == 0): ?>
              <tr><td colspan="7" class="center-align">No hay ventas registradas</td></tr>
            <?php else: while($v = $ventas->fetch_assoc()): ?>
              <tr>
                <td><?php echo $v['idVenta'] ?></td>
                <td><?php echo $v['fechaVenta'] ?></td>
                <td><?php echo $v['nombre'] . " " . $v['apellido'] ?></td>
                <td><?php echo htmlspecialchars($v['descripcion']) ?></td>
                <td>$<?php echo number_format($v['total'],2) ?></td>
                <td><a href="detalle_venta.php?id=<?php echo $v['idVenta'] ?>" class="btn-floating blue"><i class="material-icons">visibility</i></a></td>
                <td><a href="#" class="btn-floating red" onclick="eliminarVenta(<?php echo $v['idVenta'] ?>)"><i class="material-icons">delete</i></a></td>
              </tr>
            <?php endwhile; endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Función global para eliminar venta
function eliminarVenta(id) {
  Swal.fire({
    title: '¿Eliminar venta?',
    text: 'Se eliminarán todos los detalles asociados',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) location.href = 'eliminar_venta.php?id=' + id;
  });
}

// Calcular subtotal de un producto individual
function calcularSubtotalProducto(row) {
  let select = row.find('.producto-select');
  let precio = parseFloat(select.find('option:selected').data('precio')) || 0;
  let cantidad = parseInt(row.find('.cantidad').val()) || 0;
  let descuento = parseFloat(row.find('.descuento').val()) || 0;
  let subtotal = (precio * cantidad) - descuento;
  row.find('.subtotal-producto').text(subtotal.toFixed(2));
  return subtotal;
}

// Calcular totales globales
function calcularTotalesGlobales() {
  let subtotalGlobal = 0;
  let descuentoGlobal = 0;
  $('.producto-row').each(function() {
    let $row = $(this);
    let precio = parseFloat($row.find('.producto-select option:selected').data('precio')) || 0;
    let cantidad = parseInt($row.find('.cantidad').val()) || 0;
    let descuento = parseFloat($row.find('.descuento').val()) || 0;
    subtotalGlobal += precio * cantidad;
    descuentoGlobal += descuento;
    calcularSubtotalProducto($row);
  });
  let totalPagar = subtotalGlobal - descuentoGlobal;
  $('#subtotal').text(subtotalGlobal.toFixed(2));
  $('#descuento-total').text(descuentoGlobal.toFixed(2));
  $('#total-pagar').text(totalPagar.toFixed(2));
  
  // Habilitar/deshabilitar botón registrar
  let hayProducto = false;
  $('.producto-select').each(function() { if($(this).val()) hayProducto = true; });
  $('#btn_registrar').prop('disabled', !hayProducto || !$('#cliente').val());
}

// Actualizar stock mostrado y validar cantidad
function actualizarStockProducto(row) {
  let select = row.find('.producto-select');
  let stock = select.find('option:selected').data('stock') || 0;
  let cantidad = parseInt(row.find('.cantidad').val()) || 1;
  row.find('.stock-disponible').text(stock);
  if(cantidad > stock) {
    row.find('.cantidad').addClass('invalid').css('border-color', 'red');
    row.find('.stock-disponible').css('color', 'red').append(' <i class="material-icons tiny">warning</i>');
  } else {
    row.find('.cantidad').removeClass('invalid').css('border-color', '');
    row.find('.stock-disponible').css('color', '');
  }
}

$(document).ready(function(){
  // Inicializar datepicker
  $('.datepicker').datepicker({ format: 'dd/mm/yyyy', defaultDate: new Date(), setDefaultDate: true });
  
  // Función para agregar nuevo producto
  function agregarProducto() {
    if($('.producto-row').length >= 6) {
      Swal.fire('Límite', 'Máximo 6 productos por venta', 'warning');
      return;
    }
    let $original = $('.producto-row').first();
    let $clone = $original.clone();
    // Limpiar valores del clon
    $clone.find('select').val('');
    $clone.find('.cantidad').val(1);
    $clone.find('.descuento').val(0);
    $clone.find('.subtotal-producto').text('0.00');
    $clone.find('.stock-disponible').text('0');
    $clone.find('.cantidad').removeClass('invalid');
    // Eliminar cualquier evento de clic duplicado (no necesario con delegación)
    $('#productos-container').append($clone);
    calcularTotalesGlobales();
  }
  
  // Evento para agregar producto
  $('#agregar-producto').on('click', agregarProducto);
  
  // Eliminar producto (delegación para elementos dinámicos)
  $(document).on('click', '.btn-eliminar-producto', function() {
    if($('.producto-row').length > 1) {
      $(this).closest('.producto-row').remove();
      calcularTotalesGlobales();
    } else {
      Swal.fire('Atención', 'Debe haber al menos un producto', 'warning');
    }
  });
  
  // Cambio de producto: actualizar stock y totales
  $(document).on('change', '.producto-select', function(){
    let $row = $(this).closest('.producto-row');
    actualizarStockProducto($row);
    calcularTotalesGlobales();
  });
  
  // Cambio de cantidad: validar stock y actualizar
  $(document).on('change keyup', '.cantidad', function(){
    let $row = $(this).closest('.producto-row');
    let stock = $row.find('.producto-select option:selected').data('stock') || 0;
    let cantidad = parseInt($(this).val()) || 1;
    if(cantidad > stock) {
      Swal.fire('Stock insuficiente', 'Máximo disponible: ' + stock, 'error');
      $(this).val(stock);
      cantidad = stock;
    }
    if(cantidad < 1) $(this).val(1);
    actualizarStockProducto($row);
    calcularTotalesGlobales();
  });
  
  // Cambio de descuento: actualizar totales
  $(document).on('change keyup', '.descuento', function(){
    let desc = parseFloat($(this).val()) || 0;
    if(desc < 0) $(this).val(0);
    calcularTotalesGlobales();
  });
  
  // Cambio de cliente
  $('#cliente').on('change', function(){ calcularTotalesGlobales(); });
  
  // Validar formulario antes de enviar
  $('#formVenta').on('submit', function(e){
    let hayProducto = false;
    let stockCorrecto = true;
    $('.producto-row').each(function(){
      let producto = $(this).find('.producto-select').val();
      if(producto) hayProducto = true;
      let cantidad = parseInt($(this).find('.cantidad').val()) || 0;
      let stock = $(this).find('.producto-select option:selected').data('stock') || 0;
      if(cantidad > stock) stockCorrecto = false;
    });
    if(!hayProducto) {
      e.preventDefault();
      Swal.fire('Error', 'Seleccione al menos un producto', 'error');
    } else if(!stockCorrecto) {
      e.preventDefault();
      Swal.fire('Error', 'Revise las cantidades (superan el stock)', 'error');
    }
  });
  
  // Inicializar stock y totales
  $('.producto-row').each(function(){
    actualizarStockProducto($(this));
  });
  calcularTotalesGlobales();
});
</script>

<?php include '../../extend/scripts.php'; ?>
</body>
</html>