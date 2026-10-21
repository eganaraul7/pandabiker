<?php
session_start();

// Datos de conexión en ../db_config.php (config.local.php o, si no existe, config.example.php)
$cfg  = require __DIR__ . '/../db_config.php';

$conexion = new mysqli($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['db'], $cfg['port']);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}