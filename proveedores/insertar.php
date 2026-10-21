<?php
include("conexion.php");
$con = conectar();

// Eliminamos id_proveedor porque es AUTO_INCREMENT
$nombre_empresa  = $_POST['nombre_empresa'];
$nombre_contacto = $_POST['nombre_contacto'];
$telefono        = $_POST['telefono'];
$direccion       = $_POST['direccion'];
$rif             = $_POST['rif'];
$estatus         = $_POST['estatus'];

// Especificamos explícitamente las columnas en el orden correcto (sin id_proveedor ni creado_en)
$sql = "INSERT INTO proveedores (nombre_empresa, nombre_contacto, telefono, direccion, rif, estatus) 
        VALUES ('$nombre_empresa', '$nombre_contacto', '$telefono', '$direccion', '$rif', '$estatus')";

$query = mysqli_query($con, $sql);

if($query){
    Header("Location: ../index.php");
} else {
    echo "Error al registrar: " . mysqli_error($con);
}
?>