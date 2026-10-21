<?php
function conectar(){
    // Datos de conexión en ../db_config.php (config.local.php o, si no existe, config.example.php)
    $cfg = require __DIR__ . '/../db_config.php';

    $con=mysqli_connect($cfg['host'],$cfg['user'],$cfg['pass'],$cfg['db'],$cfg['port']);

    return $con;
}
?>
