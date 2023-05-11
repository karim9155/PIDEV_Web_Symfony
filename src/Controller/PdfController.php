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
use Dompdf\Dompdf;
use Knp\Snappy\Pdf;
use Dompdf\Options;

#[Route('/moyen/transport')]
class PdfController extends AbstractController
{

#[Route('/moyen/pdf', name: 'pdf_route', methods :["GET"])]
public function generatePdf(Request $request, Pdf $snappyPdf, MoyenTransportRepository $moyenTransportRepository)
{
     // Configure Dompdf according to your needs
     $pdfOptions = new Options();
     $pdfOptions->set('defaultFont', 'Arial');

     // Instantiate Dompdf with our options
     $dompdf = new Dompdf($pdfOptions);
     // Retrieve the HTML generated in our twig file
     $html = $this->renderView('moyen_transport/pdf.html.twig', [
         'moyen_transports' =>$moyenTransportRepository->findAll(),
         'image_path' => 'C:/Users/Asus/Desktop/stage/logo.jpg',
     ]);

     // Load HTML to Dompdf
     $dompdf->loadHtml($html);
     // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
     $dompdf->setPaper('A4', 'portrait');

     // Render the HTML as PDF
     $dompdf->render();
     // Output the generated PDF to Browser (inline view)
     $dompdf->stream("liste.pdf", [
         "moyen_transports" => true
     ]);
}
}

