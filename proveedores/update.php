<?php

include("conexion.php");
$con=conectar();

$id_proveedor=$_POST['id_proveedor'];
$nombre_empresa=$_POST['nombre_empresa'];
$nombre_contacto=$_POST['nombre_contacto'];
$telefono=$_POST['telefono'];
$direccion=$_POST['direccion'];
$rif=$_POST['rif'];
$estatus=$_POST['estatus'];
$sql="UPDATE proveedores SET nombre_empresa='$nombre_empresa', nombre_contacto='$nombre_contacto', telefono='$telefono', direccion='$direccion', rif='$rif', estatus='$estatus' WHERE id_proveedor='$id_proveedor'";
$query=mysqli_query($con,$sql);

    if($query){
        Header("Location: /pandabiker/index.php");
    }
?>