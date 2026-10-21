<?php

include("conexion.php");
$con=conectar();

$usuario=$_POST['usuario'];
$clave=$_POST['clave'];
$rol=$_POST['rol'];





$sql="UPDATE usuarios SET usuario='$usuario',clave='$clave',rol='$rol' WHERE usuario='$usuario'";
$query=mysqli_query($con,$sql);

    if($query){
        Header("Location: /pandabiker/index.php");
    }
?>