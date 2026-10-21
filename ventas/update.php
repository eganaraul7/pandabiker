<?php

include("conexion.php");
$con=conectar();

$id_venta=$_POST['id_venta'];
$codigo_producto=$_POST['codigo_producto'];
$cantidad=$_POST['cantidad'];
$total_venta=$_POST['total_venta'];
$forma_pago=$_POST['forma_pago'];
$fecha_venta=$_POST['fecha_venta'];




$sql="UPDATE ventas SET codigo_producto='$codigo_producto', cantidad='$cantidad', total_venta='$total_venta', forma_pago='$forma_pago', fecha_venta='$fecha_venta' WHERE id_venta='$id_venta'";
$query=mysqli_query($con,$sql);

    if($query){
        Header("Location: /pandabiker/index.php");
    }
?>