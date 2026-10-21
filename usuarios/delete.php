<?php

include("conexion.php");
$con=conectar();

$usuario=$_GET['id'];

$sql="DELETE FROM usuarios  WHERE usuario='$usuario'";
$query=mysqli_query($con,$sql);

    if($query){
        Header("Location: /pandabiker/index.php");
    }
?>
