<?php include "../../extend/header.php";
$idVenta = intval($_GET['id']);
$detalles = $con->query("SELECT P.nombre, D.cantidad, P.precio, D.descuento, (D.cantidad * P.precio - D.descuento) as subtotal FROM DetalleVenta D JOIN Producto P ON D.Producto_idProducto=P.idProducto WHERE D.Venta_idVenta=$idVenta");
?>
<div class="row">
  <div class="col s12">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Detalle de Venta #<?php echo $idVenta ?></span>
        <table class="striped">
          <thead><tr><th>Producto</th><th>Cantidad</th><th>Precio unit.</th><th>Descuento</th><th>Subtotal</th></tr></thead>
          <tbody><?php $total=0; while($d=$detalles->fetch_assoc()): $total+=$d['subtotal']; ?><tr><td><?php echo $d['nombre'] ?></td><td><?php echo $d['cantidad'] ?></td><td>$<?php echo number_format($d['precio'],2) ?></td><td>$<?php echo number_format($d['descuento'],2) ?></td><td>$<?php echo number_format($d['subtotal'],2) ?></td></tr><?php endwhile; ?></tbody>
          <tfoot><tr><th colspan="4">Total:</th><th>$<?php echo number_format($total,2) ?></th></tr></tfoot>
        </table>
        <a href="index.php" class="btn">Volver</a>
      </div>
    </div>
  </div>
</div>
<?php include '../../extend/scripts.php'; ?>