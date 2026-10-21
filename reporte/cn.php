<?php
    $cfg = require __DIR__ . '/../db_config.php';   // datos en config.local.php o config.example.php
    $conexion = mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['db'], $cfg['port']);
