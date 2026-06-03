<?php include "../../extend/header.php"; ?>
<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">NUEVA VENTA</span>
        <form id="formVenta" action="ins_venta.php" method="post">
          <div class="input-field">
            <input type="text" name="fechaVenta" id="fechaVenta" class="datepicker" required>
            <label for="fechaVenta">Fecha Venta:</label>
          </div>

          <div class="input-field">
            <select name="cliente" id="cliente" required>
              <option value="" disabled selected>Seleccione cliente</option>
              <?php 
              $cli = $con->query("SELECT idCliente, nombre, apellido FROM Cliente ORDER BY nombre"); 
              while($c = $cli->fetch_assoc()): 
              ?>
                <option value="<?php echo $c['idCliente'] ?>"><?php echo $c['nombre'] . " " . $c['apellido'] ?></option>
              <?php endwhile; ?>
            </select>
            <label>Cliente</label>
          </div>

          <div class="input-field">
            <input type="text" name="descripcion" id="descripcion" required maxlength="200">
            <label>Descripción</label>
          </div>
          
          <h5>Productos</h5>
          <div id="productos-container">
            <div class="producto-row card-panel grey lighten-5">
              <div class="row" style="margin-bottom: 0;">
                <div class="col s12 m5">
                  <select name="producto[]" class="producto-select" required>
                    <option value="" disabled selected>Seleccione producto</option>
                    <?php 
                    $prod = $con->query("SELECT idProducto, nombre, precio, stock FROM Producto WHERE estado = 'activo' AND stock > 0 ORDER BY nombre"); 
                    while($p = $prod->fetch_assoc()): 
                    ?>
                      <option value="<?php echo $p['idProducto'] ?>" 
                              data-precio="<?php echo $p['precio'] ?>" 
                              data-stock="<?php echo $p['stock'] ?>"
                              data-nombre="<?php echo htmlspecialchars($p['nombre']) ?>">
                        <?php echo htmlspecialchars($p['nombre']) ?> - $<?php echo number_format($p['precio'],2) ?> (stock: <?php echo $p['stock'] ?>)
                      </option>
                    <?php endwhile; ?>
                  </select>
                </div>
                <div class="col s12 m3">
                  <input type="number" name="cantidad[]" class="cantidad" placeholder="Cantidad" min="1" required>
                </div>
                <div class="col s12 m3">
                  <input type="number" step="0.01" name="descuento[]" class="descuento" placeholder="Descuento" value="0" min="0">
                </div>
                <div class="col s12 m1">
                  <button type="button" class="btn red remove-row" style="width:100%;">
                    <i class="material-icons">remove</i>
                  </button>
                </div>
              </div>
              <div class="row" style="margin-top: 10px; margin-bottom: 0;">
                <div class="col s12">
                  <small class="subtotal-text grey-text">Subtotal: $0.00</small>
                  <small class="stock-info grey-text" style="margin-left: 15px;"></small>
                </div>
              </div>
            </div>
          </div>
          
          <button type="button" id="add-producto" class="btn green waves-effect waves-light">
            <i class="material-icons left">add</i>Agregar producto
          </button>
          
          <!-- Resumen de venta (corregido) -->
          <div class="card-panel blue lighten-5" style="margin-top: 20px;">
            <h5>Resumen de venta</h5>
            <p><strong>Subtotal:</strong> $<span id="subtotal">0.00</span></p>
            <p><strong>Descuento total:</strong> $<span id="descuento-total">0.00</span></p>
            <p><strong>Total a pagar:</strong> $<span id="total-pagar">0.00</span></p>
          </div>
          
          <button type="submit" class="btn blue waves-effect waves-light" id="btn_registrar" disabled>
            <i class="material-icons left">attach_money</i>Registrar Venta
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">LISTADO DE VENTAS</span>
        <table class="striped responsive-table">
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
          if($ventas->num_rows == 0):
          ?>
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

<script>
// Función para eliminar venta
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
    if (result.isConfirmed) {
      location.href = 'eliminar_venta.php?id=' + id;
    }
  });
}

// Calcular totales de la venta (actualiza el resumen)
function calcularTotales() {
  var subtotal = 0;
  var descuentoTotal = 0;
  
  $('.producto-row').each(function() {
    var select = $(this).find('.producto-select');
    var precio = parseFloat(select.find('option:selected').data('precio')) || 0;
    var cantidad = parseInt($(this).find('.cantidad').val()) || 0;
    var descuento = parseFloat($(this).find('.descuento').val()) || 0;
    
    var totalProducto = precio * cantidad;
    subtotal += totalProducto;
    descuentoTotal += descuento;
    
    var subtotalProducto = totalProducto - descuento;
    $(this).find('.subtotal-text').text('Subtotal: $' + subtotalProducto.toFixed(2));
  });
  
  var totalPagar = subtotal - descuentoTotal;
  $('#subtotal').text(subtotal.toFixed(2));
  $('#descuento-total').text(descuentoTotal.toFixed(2));
  $('#total-pagar').text(totalPagar.toFixed(2));
  
  // Habilitar botón solo si hay productos seleccionados y cliente seleccionado
  var productosSeleccionados = false;
  $('.producto-select').each(function() {
    if($(this).val()) productosSeleccionados = true;
  });
  
  $('#btn_registrar').prop('disabled', !productosSeleccionados || !$('#cliente').val());
}

// Actualizar stock disponible al cambiar producto
function actualizarStockDisponible(select) {
  var row = select.closest('.producto-row');
  var stock = select.find('option:selected').data('stock') || 0;
  var cantidad = row.find('.cantidad').val() || 1;
  var stockInfo = row.find('.stock-info');
  
  if(stockInfo.length) {
    if(cantidad > stock) {
      stockInfo.css('color', 'red');
      stockInfo.html('<i class="material-icons tiny" style="font-size: 12px;">warning</i> Stock insuficiente (disponible: ' + stock + ')');
      row.find('.cantidad').addClass('invalid');
    } else {
      stockInfo.css('color', '#9e9e9e');
      stockInfo.text('Stock disponible: ' + stock);
      row.find('.cantidad').removeClass('invalid');
    }
  }
}

$(document).ready(function(){
  // Inicializar componentes
  $('select').formSelect();
  $('.datepicker').datepicker({
    format: 'dd/mm/yyyy',
    defaultDate: new Date(),
    setDefaultDate: true
  });
  
  // Agregar producto
  $('#add-producto').click(function(){
    var clone = $('.producto-row:first').clone();
    clone.find('select').val('').formSelect();
    clone.find('.cantidad').val('');
    clone.find('.descuento').val('0');
    clone.find('.subtotal-text').text('Subtotal: $0.00');
    clone.find('.stock-info').text('');
    clone.find('.cantidad').removeClass('invalid');
    $('#productos-container').append(clone);
    calcularTotales();
  });
  
  // Eliminar producto
  $(document).on('click', '.remove-row', function(){ 
    if($('.producto-row').length > 1) {
      $(this).closest('.producto-row').remove();
      calcularTotales();
    } else {
      Swal.fire('Atención', 'Debe haber al menos un producto', 'warning');
    }
  });
  
  // Validar cantidad vs stock
  $(document).on('change keyup', '.cantidad', function() {
    var row = $(this).closest('.producto-row');
    var stock = row.find('.producto-select option:selected').data('stock') || 0;
    var cantidad = parseInt($(this).val()) || 0;
    
    if(cantidad > stock) {
      Swal.fire('Error', 'La cantidad no puede superar el stock disponible (' + stock + ')', 'error');
      $(this).val(stock);
      cantidad = stock;
    }
    if(cantidad < 1) $(this).val(1);
    calcularTotales();
    actualizarStockDisponible(row.find('.producto-select'));
  });
  
  // Cambio de producto
  $(document).on('change', '.producto-select', function() {
    var row = $(this).closest('.producto-row');
    row.find('.cantidad').val(1);
    actualizarStockDisponible($(this));
    calcularTotales();
  });
  
  // Cambio de descuento
  $(document).on('change keyup', '.descuento', function() {
    var descuento = parseFloat($(this).val()) || 0;
    if(descuento < 0) $(this).val(0);
    calcularTotales();
  });
  
  // Cambio de cliente
  $('#cliente').change(function() {
    calcularTotales();
  });
  
  // Validar formulario antes de enviar
  $('#formVenta').submit(function(e) {
    var tieneProductos = false;
    var stockValido = true;
    
    $('.producto-row').each(function() {
      var producto = $(this).find('.producto-select').val();
      var cantidad = parseInt($(this).find('.cantidad').val()) || 0;
      var stock = $(this).find('.producto-select option:selected').data('stock') || 0;
      
      if(producto) {
        tieneProductos = true;
        if(cantidad > stock) {
          stockValido = false;
          Swal.fire('Error', 'La cantidad de un producto supera el stock disponible', 'error');
          return false;
        }
      }
    });
    
    if(!tieneProductos) {
      e.preventDefault();
      Swal.fire('Error', 'Debe agregar al menos un producto', 'error');
    } else if(!stockValido) {
      e.preventDefault();
    }
  });
  
  calcularTotales();
});

$('<style>.producto-row { margin-bottom: 15px; } .remove-row { margin-top: 5px; }</style>').appendTo('head');
</script>

<?php include '../../extend/scripts.php'; ?>
</body>
</html>