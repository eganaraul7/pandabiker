<?php
include("conexion.php");
$con = conectar();

// 1. Recibir datos de la cabecera
$id_proveedor   = $_POST['id_proveedor'];
$numero_factura = $_POST['numero_factura'];
$fecha_compra   = $_POST['fecha_compra'];
$total_compra   = $_POST['total_compra'];

// 2. Recibir datos del detalle (llegan como arrays)
$codigos   = $_POST['codigo_producto'];
$cantidades = $_POST['cantidad'];
$precios    = $_POST['precio_costo'];

// Paso A: Insertar en la tabla compras
$sql_compra = "INSERT INTO compras (id_proveedor, numero_factura, fecha_compra, total_compra) 
               VALUES ('$id_proveedor', '$numero_factura', '$fecha_compra', '$total_compra')";
$query_compra = mysqli_query($con, $sql_compra);

if($query_compra) {
    $id_compra_reciente = mysqli_insert_id($con);
    $todo_ok = true;

    // Paso B: Insertar cada producto del carrito en detalle_compras
    for ($i = 0; $i < count($codigos); $i++) {
        if ($codigos[$i] === '') continue; // saltar filas vacías

        $codigo_producto = $codigos[$i];
        $cantidad        = $cantidades[$i];
        $precio_costo    = $precios[$i];

        $sql_detalle = "INSERT INTO detalle_compras (id_compra, codigo_producto, cantidad, precio_costo) 
                        VALUES ('$id_compra_reciente', '$codigo_producto', '$cantidad', '$precio_costo')";
        $query_detalle = mysqli_query($con, $sql_detalle);

        if (!$query_detalle) {
            $todo_ok = false;
            echo "Error al guardar el producto $codigo_producto: " . mysqli_error($con);
        }
    }

    if ($todo_ok) {
        Header("Location: /pandabiker/index.php");
    }
} else {
    echo "Error al guardar la compra principal: " . mysqli_error($con);
}
?>