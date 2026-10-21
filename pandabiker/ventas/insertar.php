<?php
include("conexion.php");
$con = conectar();

$codigos    = $_POST['codigo_producto'];
$cantidades = $_POST['cantidad'];
$precios    = $_POST['precio_unitario'];
$forma_pago = $_POST['forma_pago'];
$fecha_venta = $_POST['fecha_venta'];

$errores = [];

// Paso 1: validar stock de TODOS los productos antes de insertar nada
for ($i = 0; $i < count($codigos); $i++) {
    if ($codigos[$i] === '') continue;

    $codigo_producto = $codigos[$i];
    $cantidad = (float) $cantidades[$i];

    $sql_stock = "SELECT stock_producto, descripcion_producto FROM inventario WHERE codigo_producto = '$codigo_producto'";
    $res_stock = mysqli_query($con, $sql_stock);
    $prod = mysqli_fetch_assoc($res_stock);

    if (!$prod) {
        $errores[] = "Producto $codigo_producto no encontrado en inventario.";
        continue;
    }

    if ($cantidad > $prod['stock_producto']) {
        $errores[] = "No se puede vender $cantidad de '" . $prod['descripcion_producto'] . "'. Stock disponible: " . $prod['stock_producto'] . ".";
    }
}

if (count($errores) > 0) {
    echo "<h4>No se pudo registrar la venta:</h4><ul>";
    foreach ($errores as $e) {
        echo "<li>" . $e . "</li>";
    }
    echo "</ul><a href='../pandabiker/ventas/crudventas.php'>Volver</a>";
    exit;
}

// Paso 2: insertar cada producto como una fila en ventas (el trigger resta stock por fila)
$todo_ok = true;
for ($i = 0; $i < count($codigos); $i++) {
    if ($codigos[$i] === '') continue;

    $codigo_producto = $codigos[$i];
    $cantidad = $cantidades[$i];
    $precio_unitario = (float) $precios[$i];
    $total_linea = $cantidad * $precio_unitario;

    $sql = "INSERT INTO ventas (codigo_producto, cantidad, total_venta, forma_pago, fecha_venta) 
            VALUES ('$codigo_producto', '$cantidad', '$total_linea', '$forma_pago', '$fecha_venta')";
    $query = mysqli_query($con, $sql);

    if (!$query) {
        $todo_ok = false;
        echo "Error al registrar $codigo_producto: " . mysqli_error($con);
    }
}

if ($todo_ok) {
    Header("Location: /pandabiker/index.php");
}
?>