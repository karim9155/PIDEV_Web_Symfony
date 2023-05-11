<?php
namespace App\Controller;

use App\Entity\MoyenTransport;
use App\Form\MoyenTransportType;
use App\Repository\MoyenTransportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use TCPDF;

#[Route('/moyen/transport')]
class PdffController extends AbstractController
{
    #[Route('/moyen/pdff', name: 'pdff_route', methods :["GET"])]
public function pdff(Request $request, MoyenTransportRepository $mr)
{
    $moyens = $mr->findAll();
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Add a page
    $pdf->AddPage();

    // Set the font

$pdf->SetFont('helvetica', '', 14);

$imagePath = $this->getParameter('kernel.project_dir').'/logo.jpg';
$pdf->Image($imagePath, 10, 15, 40, 0, '', '', '', false, 300);

// Add some content
$pdf->Ln(20); // add space after image
$pdf->SetFillColor(63, 81, 181); // Blue background color
$pdf->SetTextColor(255, 255, 255); // White text color
$pdf->Cell(0, 9, 'Informations sur les moyens de transport', 1, 1, 'C', 1, '', 0, true);

$pdf->Ln(); // add space after title
$pdf->SetFillColor(255, 255, 255); // White background color
$pdf->SetTextColor(0, 0, 0); // Black text color
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(50, 7, 'Numéro', 1, 0, 'C', 1);
$pdf->Cell(50, 7, 'Type', 1, 0, 'C', 1);
$pdf->Cell(50, 7, 'Note', 1, 0, 'C', 1);
$pdf->Cell(40, 7, 'Etat', 1, 1, 'C', 1);

$pdf->SetFont('helvetica', '', 12);
foreach ($moyens as $moyen) {
    $pdf->Cell(50, 7, $moyen->getNum(), 1, 0, 'C', 0);
    $pdf->Cell(50, 7, $moyen->getTypeVehicule(), 1, 0, 'C', 0);
    $pdf->Cell(50, 7, $moyen->getNote(), 1, 0, 'C', 0);
    $pdf->Cell(40, 7, $moyen->getEtat(), 1, 1, 'C', 0);
}
$pdf->Cell(0, 10, 'SwiftTransit', 0, 1, 'C');

// Set the font
// $pdf->SetFont('helvetica', '', 11);

// Add an image



// Output the PDF as a response
return new Response($pdf->Output('informations-moyens-transport.pdf', 'I'));

    
}
}

