<?php
$usuario=$_POST['usuario'];
$contraseña=$_POST['contraseña'];
session_start();
$_SESSION['usuario']=$usuario;

$conexion=mysqli_connect("localhost","root","","panda");


$consulta="SELECT * FROM usuarios where usuario='$usuario' and clave='$contraseña'";

$resultado=mysqli_query($conexion,$consulta);

$filas=mysqli_fetch_array($resultado);

if($filas['rol']==1){ //administrador
        header("location: /pandabiker/index.php");

}else

if($filas['rol']==2){ //Empleado
    header("location: /pandabiker/index.php");

}
else{

 
    echo '<script>alert("Welcome to Geeks for Geeks")</script>';
    header("location: /pandabiker/index2.html");

}

mysqli_free_result($resultado);
mysqli_close($conexion);
