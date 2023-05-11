<?php

namespace App\Controller;

use App\Entity\Trajet;
use App\Entity\Iteneraire;
use App\Form\TrajetType;
use App\Repository\TrajetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Symfony\Component\HttpFoundation\StreamedResponse;


#[Route('/trajet')]
class TrajetController extends AbstractController
{
    

    #[Route('/', name: 'app_trajet_index', methods: ['GET'])]
    public function index(Request $request,TrajetRepository $trajetRepository): Response
    {
        $searchTerm = $request->query->get('q');
        

        // Retrieve the trajets from the database based on the search term (if provided)
        $trajets = $this->getDoctrine()->getRepository(Trajet::class)->findBySearchTerm($searchTerm);
    
        // Render the template with the trajets and the search term
        return $this->render('trajet/index.html.twig', [
            'trajets' => $trajets,
            'searchTerm' => $searchTerm,
    
   
      
        ]);
    

    }

    #[Route('/new', name: 'app_trajet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TrajetRepository $trajetRepository): Response
    {
        $trajet = new Trajet();
        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trajetRepository->save($trajet, true);

            return $this->redirectToRoute('app_trajet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trajet/new.html.twig', [
            'trajet' => $trajet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trajet_show', methods: ['GET'])]
    public function show(Trajet $trajet): Response
    {
        return $this->render('trajet/show.html.twig', [
            'trajet' => $trajet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_trajet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trajet $trajet, TrajetRepository $trajetRepository): Response
    {
        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trajetRepository->save($trajet, true);

            return $this->redirectToRoute('app_trajet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trajet/edit.html.twig', [
            'trajet' => $trajet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_trajet_delete', methods: ['POST'])]
    public function delete(Request $request, Trajet $trajet, TrajetRepository $trajetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trajet->getId(), $request->request->get('_token'))) {
            $trajetRepository->remove($trajet, true);
        }

        return $this->redirectToRoute('app_trajet_index', [], Response::HTTP_SEE_OTHER);
    }

/**
 * @Route("/trajet/export", name="app_trajet_export")
 */
public function exportToExcelAction() : Response
{
    // Get the data to be exported
    $trajets = $this->getDoctrine()->getRepository(Trajet::class)->findAll();

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();

    // Add a new worksheet
    $sheet = $spreadsheet->getActiveSheet();

    // Write the headers to the worksheet
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Temps parcours');
    $sheet->setCellValue('C1', 'Depart');
    $sheet->setCellValue('D1', 'Destination');

    

    // Write the data to the worksheet
    $row = 2;
    foreach ($trajets as $trajet) {
        $sheet->setCellValue('A' . $row, $trajet->getId());
        $sheet->setCellValue('B' . $row, $trajet->getTempsParcours());
        $sheet->setCellValue('C' . $row, $trajet->getPtsDepart());
        $sheet->setCellValue('D' . $row, $trajet->getPtsArrive());
        
        
        

        $row++;
    }

    // Create a new Xlsx writer object
    $writer = new Xlsx($spreadsheet);

    // Create a response object with the Excel file
    $response = new StreamedResponse(function () use ($writer) {
        $writer->save('php://output');
    });

    // Set the content type header
    $response->headers->set('Content-Type', 'application/vnd.ms-excel');

    // Set the content disposition header to force a download
    $dispositionHeader = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'trajets.xlsx');
    $response->headers->set('Content-Disposition', $dispositionHeader);

    // Send the response
    return $response;
}

}