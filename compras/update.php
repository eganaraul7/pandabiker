<?php
include("conexion.php");
$con = conectar();

$id_compra      = $_POST['id_compra'];
$id_proveedor   = $_POST['id_proveedor'];
$numero_factura = $_POST['numero_factura'];
$fecha_compra   = $_POST['fecha_compra'];
$total_compra   = $_POST['total_compra'];

$ids_detalle = $_POST['id_detalle'];
$codigos     = $_POST['codigo_producto'];
$cantidades  = $_POST['cantidad'];
$precios     = $_POST['precio_costo'];

// 1. Actualizar cabecera
$sql_compra = "UPDATE compras SET 
                id_proveedor = '$id_proveedor', 
                numero_factura = '$numero_factura', 
                fecha_compra = '$fecha_compra', 
                total_compra = '$total_compra' 
                WHERE id_compra = '$id_compra'";
$query_compra = mysqli_query($con, $sql_compra);

$todo_ok = true;

// 2. Recorrer cada fila: si trae id_detalle, UPDATE; si viene vacío (fila agregada nueva), INSERT
for ($i = 0; $i < count($codigos); $i++) {
    if ($codigos[$i] === '') continue;

    $id_detalle      = $ids_detalle[$i];
    $codigo_producto = $codigos[$i];
    $cantidad        = $cantidades[$i];
    $precio_costo    = $precios[$i];

    if ($id_detalle !== '') {
        $sql_detalle = "UPDATE detalle_compras SET 
                        codigo_producto = '$codigo_producto', 
                        cantidad = '$cantidad', 
                        precio_costo = '$precio_costo' 
                        WHERE id_detalle = '$id_detalle' AND id_compra = '$id_compra'";
    } else {
        $sql_detalle = "INSERT INTO detalle_compras (id_compra, codigo_producto, cantidad, precio_costo)
                        VALUES ('$id_compra', '$codigo_producto', '$cantidad', '$precio_costo')";
    }

    $query_detalle = mysqli_query($con, $sql_detalle);
    if (!$query_detalle) {
        $todo_ok = false;
        echo "Error al actualizar el producto $codigo_producto: " . mysqli_error($con);
    }
}

if ($query_compra && $todo_ok) {
    Header("Location: /pandabiker/index.php");
} else {
    echo "Error al actualizar la compra: " . mysqli_error($con);
}
?>