<?php
// PDF report generation adapted to main_view structure
require_once __DIR__ . '/config/conexion.php';
$conexion = dbConectar();
require_once __DIR__ . '/../Cliente/lib/fpdf/fpdf.php';

class PDF extends FPDF {
    function Header() {
        // try to find an image in Cliente/img/itssmt.png
        $img = __DIR__ . '/../Cliente/img/itssmt.png';
        if (file_exists($img)) {
            $this->Image($img, 70, 8, 33);
        }
        $this->Ln(20);
        $this->SetFont('Arial','B',15);
        $this->Cell(0,10,'REPORTE DE USUARIOS',0,1,'C');
        $this->Ln(10);

        $this->SetFillColor(210, 180, 140);
        $this->SetFont('Arial','B',11);
        $this->SetX(($this->GetPageWidth() - (30+40+50+70)) / 2);
        $this->Cell(30,10,'Nombre',1,0,'C', true);
        $this->Cell(40,10,'Paterno',1,0,'C', true);
        $this->Cell(50,10,'Materno',1,0,'C', true);
        $this->Cell(70,10,'Telefono',1,0,'C', true);
        $this->Ln();
    }
}

$consulta = 'SELECT nombres, apa, ama, telefonos FROM users';
$resultado = $conexion->query($consulta);

$pdf = new PDF('L');
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

while($row = $resultado->fetch_assoc()) {
    $pdf->SetX(($pdf->GetPageWidth() - (30+40+50+70)) / 2);
    $pdf->Cell(30,10,utf8_decode($row['nombres']),1,0,'C');
    $pdf->Cell(40,10,utf8_decode($row['apa']),1,0,'C');
    $pdf->Cell(50,10,utf8_decode($row['ama']),1,0,'C');
    $pdf->Cell(70,10,utf8_decode($row['telefonos']),1,0,'C');
    $pdf->Ln();
}

$pdf->Output();
?>
