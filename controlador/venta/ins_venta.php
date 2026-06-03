<?php
include __DIR__ . '/../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente = intval($_POST['cliente']);
    $fechaVenta = trim($_POST['fechaVenta']);
    $descripcion = trim($_POST['descripcion']);
    $productos = $_POST['producto'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];
    $descuentos = $_POST['descuento'] ?? [];

    if (empty($cliente) || empty($fechaVenta) || empty($descripcion)) {
        header('location:../../extend/alerta.php?msg=Faltan datos obligatorios&c=ve&p=in&t=error');
        exit;
    }

    $tieneProductos = false;
    foreach ($productos as $prod) if (!empty($prod)) { $tieneProductos = true; break; }
    if (!$tieneProductos) {
        header('location:../../extend/alerta.php?msg=Debe agregar al menos un producto&c=ve&p=in&t=error');
        exit;
    }

    $con->begin_transaction();
    try {
        // Insertar venta
        $stmt = $con->prepare("INSERT INTO Venta (fechaVenta, descripcion, Cliente_idCliente) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $fechaVenta, $descripcion, $cliente);
        if (!$stmt->execute()) throw new Exception("Error al registrar venta");
        $idVenta = $con->insert_id;
        $stmt->close();

        $stmtDetalle = $con->prepare("INSERT INTO DetalleVenta (cantidad, descuento, Producto_idProducto, Venta_idVenta) VALUES (?, ?, ?, ?)");
        $stmtStock = $con->prepare("UPDATE Producto SET stock = stock - ? WHERE idProducto = ?");

        for ($i = 0; $i < count($productos); $i++) {
            if (empty($productos[$i])) continue;
            $prod = intval($productos[$i]);
            $cant = intval($cantidades[$i]);
            $desc = floatval($descuentos[$i]);

            // Verificar stock
            $check = $con->prepare("SELECT stock FROM Producto WHERE idProducto = ?");
            $check->bind_param("i", $prod);
            $check->execute();
            $res = $check->get_result();
            $stockActual = $res->fetch_assoc()['stock'];
            $check->close();
            if ($cant > $stockActual) throw new Exception("Stock insuficiente para producto ID $prod");

            $stmtDetalle->bind_param("idii", $cant, $desc, $prod, $idVenta);
            if (!$stmtDetalle->execute()) throw new Exception("Error al insertar detalle");

            $stmtStock->bind_param("ii", $cant, $prod);
            if (!$stmtStock->execute()) throw new Exception("Error al actualizar stock");
        }
        $stmtDetalle->close();
        $stmtStock->close();

        $con->commit();
        header('location:../../extend/alerta.php?msg=Venta registrada correctamente&c=ve&p=in&t=success');
    } catch (Exception $e) {
        $con->rollback();
        header('location:../../extend/alerta.php?msg=' . urlencode($e->getMessage()) . '&c=ve&p=in&t=error');
    }
    $con->close();
} else {
    header('location:../../extend/alerta.php?msg=Acceso no válido&c=ve&p=in&t=error');
}
?>