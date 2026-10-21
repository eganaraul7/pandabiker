<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    public $subtitulo = 'Reporte de Ventas';

    function Header()
    {
        $this->Image('fondo.jpg', 0, 0, 210, 297);
        $this->SetXY(10, 10);

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('PANDA BIKER C.A.'), 0, 1, 'C');

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 5, utf8_decode($this->subtitulo), 0, 1, 'C');
        $this->Ln(4);

        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(52, 73, 94);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(12, 7, 'ID',          1, 0, 'C', true);
        $this->Cell(27, 7, 'Codigo',      1, 0, 'C', true);
        $this->Cell(67, 7, 'Descripcion', 1, 0, 'C', true);
        $this->Cell(16, 7, 'Cantidad',    1, 0, 'C', true);
        $this->Cell(20, 7, 'Monto Total', 1, 0, 'C', true);
        $this->Cell(25, 7, 'Forma Pago',  1, 0, 'C', true);
        $this->Cell(20, 7, 'Fecha',       1, 1, 'C', true);
        $this->SetTextColor(0, 0, 0);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

require("cn.php");

// Filtro por fecha: ?fecha=2026-09-13 (un día), ?desde=...&hasta=... (rango), ?todo=1 (histórico completo)
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;
$desde = isset($_GET['desde']) ? $_GET['desde'] : null;
$hasta = isset($_GET['hasta']) ? $_GET['hasta'] : null;

$pdf = new PDF();

$base_sql = "SELECT v.*, i.descripcion_producto FROM ventas v LEFT JOIN inventario i ON v.codigo_producto = i.codigo_producto";

if ($fecha) {
    $fecha_esc = mysqli_real_escape_string($conexion, $fecha);
    $consulta = "$base_sql WHERE v.fecha_venta = '$fecha_esc' ORDER BY v.id_venta";
    $pdf->subtitulo = 'Reporte de Ventas del ' . $fecha_esc;
} elseif ($desde && $hasta) {
    $desde_esc = mysqli_real_escape_string($conexion, $desde);
    $hasta_esc = mysqli_real_escape_string($conexion, $hasta);
    $consulta = "$base_sql WHERE v.fecha_venta BETWEEN '$desde_esc' AND '$hasta_esc' ORDER BY v.id_venta";
    $pdf->subtitulo = 'Reporte de Ventas del ' . $desde_esc . ' al ' . $hasta_esc;
} elseif (isset($_GET['todo'])) {
    $consulta = "$base_sql ORDER BY v.id_venta";
    $pdf->subtitulo = 'Reporte de Ventas - Historico Completo';
} else {
    // Sin filtro: por defecto solo el día de hoy, para no acumular todo el historial
    $hoy = date('Y-m-d');
    $consulta = "$base_sql WHERE v.fecha_venta = '$hoy' ORDER BY v.id_venta";
    $pdf->subtitulo = 'Reporte de Ventas del ' . $hoy;
}

$resultado = mysqli_query($conexion, $consulta);

$pdf->AliasNbPages();
$pdf->SetMargins(15, 10, 15);
$pdf->AddPage();
$pdf->SetFont('Arial', '', 9);

$fill = false;
while ($row = $resultado->fetch_assoc()) {

    if ($fill) {
        $pdf->SetFillColor(240, 240, 240);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }

    $descripcion = $row['descripcion_producto'] !== null ? $row['descripcion_producto'] : '(sin descripcion)';

    $pdf->Cell(12, 6, utf8_decode($row['id_venta']),        1, 0, 'C', true);
    $pdf->Cell(27, 6, utf8_decode($row['codigo_producto']), 1, 0, 'C', true);
    $pdf->Cell(67, 6, utf8_decode($descripcion),             1, 0, 'L', true);
    $pdf->Cell(16, 6, utf8_decode($row['cantidad']),        1, 0, 'C', true);
    $pdf->Cell(20, 6, utf8_decode($row['total_venta']),     1, 0, 'C', true);
    $pdf->Cell(25, 6, utf8_decode($row['forma_pago']),      1, 0, 'C', true);
    $pdf->Cell(20, 6, utf8_decode($row['fecha_venta']),     1, 1, 'C', true);

    $fill = !$fill;
}

$pdf->Output();
?>