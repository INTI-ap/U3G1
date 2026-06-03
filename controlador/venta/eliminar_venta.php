<?php
include __DIR__ . '/../../conexion/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('location:../../extend/alerta.php?msg=ID de venta no válido&c=ve&p=in&t=error');
    exit;
}

$idVenta = intval($_GET['id']);

$con->begin_transaction();

try {
    // Obtener los detalles de la venta para restaurar stock
    $stmtDetalles = $con->prepare("SELECT Producto_idProducto, cantidad FROM DetalleVenta WHERE Venta_idVenta = ?");
    $stmtDetalles->bind_param("i", $idVenta);
    $stmtDetalles->execute();
    $resDetalles = $stmtDetalles->get_result();

    $stmtUpdateStock = $con->prepare("UPDATE Producto SET stock = stock + ? WHERE idProducto = ?");

    while ($detalle = $resDetalles->fetch_assoc()) {
        $producto = $detalle['Producto_idProducto'];
        $cantidad = $detalle['cantidad'];
        $stmtUpdateStock->bind_param("ii", $cantidad, $producto);
        if (!$stmtUpdateStock->execute()) {
            throw new Exception("Error al restaurar stock del producto $producto");
        }
    }
    $stmtDetalles->close();
    $stmtUpdateStock->close();

    // Eliminar los detalles de venta
    $stmtDelDetalles = $con->prepare("DELETE FROM DetalleVenta WHERE Venta_idVenta = ?");
    $stmtDelDetalles->bind_param("i", $idVenta);
    if (!$stmtDelDetalles->execute()) {
        throw new Exception("Error al eliminar los detalles de venta");
    }
    $stmtDelDetalles->close();

    // Eliminar la venta
    $stmtDelVenta = $con->prepare("DELETE FROM Venta WHERE idVenta = ?");
    $stmtDelVenta->bind_param("i", $idVenta);
    if (!$stmtDelVenta->execute()) {
        throw new Exception("Error al eliminar la venta");
    }
    $stmtDelVenta->close();

    $con->commit();
    header('location:../../extend/alerta.php?msg=Venta eliminada correctamente&c=ve&p=in&t=success');

} catch (Exception $e) {
    $con->rollback();
    $errorMsg = urlencode($e->getMessage());
    header("location:../../extend/alerta.php?msg=$errorMsg&c=ve&p=in&t=error");
}

$con->close();
?>