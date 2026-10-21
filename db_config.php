<?php
/*
 * Devuelve los datos de conexión a la base de datos.
 * Usa config.local.php (privado, no se versiona) si existe; si no, config.example.php (valores de XAMPP).
 * Todos los archivos de conexión del sistema (conexion.php de cada carpeta, php/db.php, reporte/cn.php y rol/validar.php)
 * leen sus datos de aquí, así solo hay un sitio donde cambiarlos.
 */
$local = __DIR__ . '/config.local.php';
return require (is_file($local) ? $local : __DIR__ . '/config.example.php');
