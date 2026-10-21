<?php

include("conexion.php");
$con=conectar();

$codigo_producto=$_POST['codigo_producto'];
$descripcion_producto=$_POST['descripcion_producto'];
$marca_producto=$_POST['marca_producto'];
$precio_compra=$_POST['precio_compra'];
$precio_venta=$_POST['precio_venta'];
$stock_producto=$_POST['stock_producto'];



$sql="UPDATE inventario SET  codigo_producto='$codigo_producto',descripcion_producto='$descripcion_producto',marca_producto='$marca_producto',precio_compra='$precio_compra',precio_venta='$precio_venta',stock_producto='$stock_producto' WHERE codigo_producto='$codigo_producto'";
$query=mysqli_query($con,$sql);

    if($query){
        Header("Location: /pandabiker/index.php");
    }
?>