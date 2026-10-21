<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "panda";

$conexion = new mysqli($host, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}