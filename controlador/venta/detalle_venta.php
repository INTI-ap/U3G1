<?php
include "../../extend/header.php";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('location:../../extend/alerta.php?msg=ID de venta no válido&c=ve&p=in&t=error');
    exit;
}

$idVenta = intval($_GET['id']);

// Obtener datos de la venta y cliente
$stmtVenta = $con->prepare("
    SELECT V.idVenta, V.fechaVenta, V.descripcion, C.nombre, C.apellido 
    FROM Venta V 
    JOIN Cliente C ON V.Cliente_idCliente = C.idCliente 
    WHERE V.idVenta = ?
");
$stmtVenta->bind_param("i", $idVenta);
$stmtVenta->execute();
$resVenta = $stmtVenta->get_result();
$venta = $resVenta->fetch_assoc();
$stmtVenta->close();

if (!$venta) {
    header('location:../../extend/alerta.php?msg=Venta no encontrada&c=ve&p=in&t=error');
    exit;
}

// Obtener detalles
$stmtDetalles = $con->prepare("
    SELECT P.nombre, D.cantidad, P.precio, D.descuento, 
           (D.cantidad * P.precio - D.descuento) as subtotal 
    FROM DetalleVenta D 
    JOIN Producto P ON D.Producto_idProducto = P.idProducto 
    WHERE D.Venta_idVenta = ?
");
$stmtDetalles->bind_param("i", $idVenta);
$stmtDetalles->execute();
$detalles = $stmtDetalles->get_result();
$stmtDetalles->close();
?>

<div class="row">
  <div class="col s12 m10 offset-m1">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Detalle de Venta #<?php echo $venta['idVenta'] ?></span>
        <p><strong>Fecha:</strong> <?php echo $venta['fechaVenta'] ?></p>
        <p><strong>Cliente:</strong> <?php echo $venta['nombre'] . " " . $venta['apellido'] ?></p>
        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($venta['descripcion']) ?></p>
        
        <table class="striped">
          <thead>
            <tr><th>Producto</th><th>Cantidad</th><th>Precio unit.</th><th>Descuento</th><th>Subtotal</th></tr>
          </thead>
          <tbody>
          <?php 
          $total = 0;
          while($d = $detalles->fetch_assoc()): 
              $total += $d['subtotal'];
          ?>
            <tr>
              <td><?php echo htmlspecialchars($d['nombre']) ?></td>
              <td><?php echo $d['cantidad'] ?></td>
              <td>$<?php echo number_format($d['precio'],2) ?></td>
              <td>$<?php echo number_format($d['descuento'],2) ?></td>
              <td>$<?php echo number_format($d['subtotal'],2) ?></td>
            </tr>
          <?php endwhile; ?>
          </tbody>
          <tfoot>
            <tr><th colspan="4">Total</th><th>$<?php echo number_format($total,2) ?></th></tr>
          </tfoot>
        </table>
        <div class="row">
          <div class="col s12 right-align">
            <a href="index.php" class="btn grey">Volver</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../extend/scripts.php'; ?>
</body>
</html>