<?php

include("conexion.php");
$con=conectar();

$codigo_producto=$_GET['id'];

$sql="DELETE FROM inventario  WHERE codigo_producto='$codigo_producto'";
$query=mysqli_query($con,$sql);

    if($query){
        Header("Location: /pandabiker/index.php");
    }
?>
