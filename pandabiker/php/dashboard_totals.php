<?php
// php/dashboard_totals.php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

// Contar Inventario
$inventario = $conexion->query('SELECT COUNT(*) as total FROM inventario');
$inventario_total = $inventario ? (int)$inventario->fetch_assoc()['total'] : 0;

// Contar ventas
$ventas = $conexion->query('SELECT COUNT(*) as total FROM ventas');
$ventas_total = $ventas ? (int)$ventas->fetch_assoc()['total'] : 0;

// Contar usuarios
$usuarios = $conexion->query('SELECT COUNT(*) as total FROM usuarios');
$usuarios_total = $usuarios ? (int)$usuarios->fetch_assoc()['total'] : 0;

$conexion->close();

$response = [
    'inventario' => $inventario_total,
    'ventas' => $ventas_total,
    'usuarios' => $usuarios_total
];
echo json_encode($response);
