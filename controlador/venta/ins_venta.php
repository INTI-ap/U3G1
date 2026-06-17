<?php
include __DIR__ . '/../../conexion/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente = intval($_POST['cliente']);
    $fechaVenta = $con->real_escape_string(htmlentities($_POST['fechaVenta']));
    $descripcion = $con->real_escape_string(htmlentities($_POST['descripcion']));
    $productos = $_POST['producto'];
    $cantidades = $_POST['cantidad'];
    $descuentos = $_POST['descuento'];
    
    // Validar que haya al menos un producto
    $tieneProductos = false;
    foreach($productos as $prod) {
        if(!empty($prod)) {
            $tieneProductos = true;
            break;
        }
    }
    
    if(!$tieneProductos) {
        header('location:../../extend/alerta.php?msg=Debe agregar al menos un producto&c=ve&p=in&t=error');
        exit;
    }
    
    $con->begin_transaction();
    
    try {
        // Insertar la venta
        $insVenta = $con->query("INSERT INTO Venta VALUES (NULL, '$fechaVenta', '$descripcion', $cliente)");
        if(!$insVenta) throw new Exception("Error al registrar la venta");
        
        $idVenta = $con->insert_id;
        
        // Insertar detalles y actualizar stock
        for($i = 0; $i < count($productos); $i++) {
            if(empty($productos[$i])) continue;
            
            $prod = intval($productos[$i]);
            $cant = intval($cantidades[$i]);
            $desc = floatval($descuentos[$i]);
            
            // Verificar stock nuevamente
            $checkStock = $con->query("SELECT stock FROM Producto WHERE idProducto = $prod");
            $stockActual = $checkStock->fetch_assoc()['stock'];
            if($cant > $stockActual) throw new Exception("Stock insuficiente para producto ID: $prod");
            
            // Insertar detalle
            $insDetalle = $con->query("INSERT INTO DetalleVenta VALUES (NULL, $cant, $desc, $prod, $idVenta)");
            if(!$insDetalle) throw new Exception("Error al insertar detalle de venta");
            
            // Actualizar stock
            $newStock = $stockActual - $cant;
            $upStock = $con->query("UPDATE Producto SET stock = $newStock WHERE idProducto = $prod");
            if(!$upStock) throw new Exception("Error al actualizar stock");
        }
        
        $con->commit();
        header('location:../../extend/alerta.php?msg=Venta registrada correctamente&c=ve&p=in&t=success');
        
    } catch(Exception $e) {
        $con->rollback();
        header('location:../../extend/alerta.php?msg=' . urlencode($e->getMessage()) . '&c=ve&p=in&t=error');
    }
    
} else {
    header('location:../../extend/alerta.php?msg=Acceso no válido&c=ve&p=in&t=error');
}
$con->close();
?>