<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('fondo.jpg', 0, 0, 210, 297);
        $this->SetXY(10, 10);

        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('PANDA BIKER C.A.'), 0, 1, 'C');

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 5, utf8_decode('Reporte de Compras'), 0, 1, 'C');
        $this->Ln(4);

        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(52, 73, 94);
        $this->SetTextColor(255, 255, 255);
        // Anchos de celda ajustados para sumar 180 (margen 15 izq y 15 der = 180 + 30 = 210 que es la hoja A4)
        $this->Cell(15, 7, 'ID', 1, 0, 'C', true);
        $this->Cell(42, 7, 'Proveedor',  1, 0, 'C', true);
        $this->Cell(32, 7, 'Nro. Factura', 1, 0, 'C', true);
        $this->Cell(20, 7, 'Fecha',      1, 0, 'C', true);
        $this->Cell(17, 7, 'Cantidad',      1, 0, 'C', true);
        $this->Cell(23, 7, 'Precio unitario',     1, 0, 'C', true);
        $this->Cell(20, 7, 'Total',      1, 1, 'C', true);
        $this->SetTextColor(0, 0, 0);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Asegúrate de usar tu archivo de conexión correcto (conexion.php o cn.php)
require("cn.php");
// Borramos la línea de $con = conectar() porque cn.php no la necesita

// Consulta uniendo compras, detalle_compras y proveedores para traer los nombres y datos reales
$consulta = "SELECT c.id_compra, p.nombre_empresa, c.numero_factura, c.fecha_compra, d.cantidad, d.precio_costo, c.total_compra 
                FROM compras c 
                INNER JOIN proveedores p ON c.id_proveedor = p.id_proveedor 
                INNER JOIN detalle_compras d ON c.id_compra = d.id_compra";

// Cambiamos $con por $conexion para que coincida con tu cn.php
$resultado = mysqli_query($conexion, $consulta);

$pdf = new PDF();
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

    $pdf->Cell(15, 6, utf8_decode($row['id_compra']),      1, 0, 'C', true);
    $pdf->Cell(42, 6, utf8_decode($row['nombre_empresa']), 1, 0, 'L', true);
    $pdf->Cell(32, 6, utf8_decode($row['numero_factura']), 1, 0, 'C', true);
    $pdf->Cell(20, 6, utf8_decode(date("Y-m-d", strtotime($row['fecha_compra']))), 1, 0, 'C', true);
    $pdf->Cell(17, 6, utf8_decode($row['cantidad']),       1, 0, 'C', true);
    $pdf->Cell(23, 6, utf8_decode($row['precio_costo']),         1, 0, 'R', true);
    $pdf->Cell(20, 6, utf8_decode($row['total_compra']),   1, 1, 'R', true);

    $fill = !$fill;
}

$pdf->Output();
?>