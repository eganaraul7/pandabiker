<?php
include("conexion.php");
$con=conectar();

$usuario=$_POST['usuario'];
$clave=$_POST['clave'];
$rol=$_POST['rol'];



$sql="INSERT INTO usuarios VALUES('$usuario','$clave','$rol')";
$query= mysqli_query($con,$sql);


if($query){
    Header("Location: /pandabiker/index.php");
  
    
 
}else {
}



?>