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
        $this->Cell(0, 5, utf8_decode('Reporte de Inventario'), 0, 1, 'C');
        $this->Ln(4);

        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(52, 73, 94);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(27, 7, 'Codigo',         1, 0, 'C', true);
        $this->Cell(67, 7, 'Descripcion',    1, 0, 'C', true);
        $this->Cell(28, 7, 'Marca',          1, 0, 'C', true);
        $this->Cell(25, 7, 'Precio Compra',  1, 0, 'C', true);
        $this->Cell(22, 7, 'Precio Venta',   1, 0, 'C', true);
        $this->Cell(17, 7, 'Stock',          1, 1, 'C', true);
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
$consulta  = "SELECT * FROM inventario";
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

    $pdf->Cell(27, 6, utf8_decode($row['codigo_producto']),     1, 0, 'C', true);
    $pdf->Cell(67, 6, utf8_decode($row['descripcion_producto']),1, 0, 'C', true);
    $pdf->Cell(28, 6, utf8_decode($row['marca_producto']),      1, 0, 'C', true);
    $pdf->Cell(25, 6, utf8_decode($row['precio_compra']),       1, 0, 'C', true);
    $pdf->Cell(22, 6, utf8_decode($row['precio_venta']),        1, 0, 'C', true);
    $pdf->Cell(17, 6, utf8_decode($row['stock_producto']),      1, 1, 'C', true);

    $fill = !$fill;
}

$pdf->Output();
?>