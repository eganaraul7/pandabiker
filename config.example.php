<?php
/*
 * Configuración de la conexión a MySQL / MariaDB (EJEMPLO, se sube a GitHub).
 *
 * Estos son los valores por defecto de una instalación local de XAMPP: usuario root sin contraseña.
 * Sirven para probar el sistema en tu ordenador, pero NO son seguros para producción.
 *
 * Para usar otros datos, copia este archivo como config.local.php (está en .gitignore y no se sube)
 * y cambia los valores allí. Si config.local.php no existe, se usan los de este archivo.
 */
return [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'db'   => 'panda',
    'port' => 3306,
];
