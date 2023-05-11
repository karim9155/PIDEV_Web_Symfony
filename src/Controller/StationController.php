<?php

namespace App\Controller;

use App\Entity\Station;
use App\Form\StationType;
use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Endroid\QrCode\QrCode;

use Endroid\QrCode\Writer\PngWriter;


#[Route('/station')]
class StationController extends AbstractController
{
    #[Route('/', name: 'app_station_index', methods: ['GET'])]
    public function index(StationRepository $stationRepository): Response
    {
        
        return $this->render('station/index.html.twig', [
            'stations' => $stationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_station_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StationRepository $stationRepository): Response
    {
        $station = new Station();
        $form = $this->createForm(StationType::class, $station);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stationRepository->save($station, true);

            return $this->redirectToRoute('app_station_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('station/new.html.twig', [
            'station' => $station,
            'form' => $form,
        ]);
    }

     #[Route('/{id}', name: 'app_station_show', methods: ['GET'])]
    public function show(Station $station): Response
    {
        return $this->render('station/show.html.twig', [
            'station' => $station,
        ]);
    }
 /**
 * @Route("/station/{id}/qr-code", name="app_station_generate_qr_code")
 */
public function generateQrCode($id)
{
    // Find the station by ID
    $station = $this->getDoctrine()->getRepository(Station::class)->find($id);

    if (!$station) {
        throw $this->createNotFoundException('The station does not exist');
    }

    // Generate a QR code with the station's longAlt value as the data
    $qrCode = new QrCode($station->getLongAlt());

    
    // Set the content type of the response to image/png
    $response = new Response($qrCode->writeString(), Response::HTTP_OK, ['Content-Type' => 'image/png']);

    return $response;
}
    #[Route('/{id}/edit', name: 'app_station_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Station $station, StationRepository $stationRepository): Response
    {
        $form = $this->createForm(StationType::class, $station);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stationRepository->save($station, true);

            return $this->redirectToRoute('app_station_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('station/edit.html.twig', [
            'station' => $station,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_station_delete', methods: ['POST'])]
    public function delete(Request $request, Station $station, StationRepository $stationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$station->getId(), $request->request->get('_token'))) {
            $stationRepository->remove($station, true);
        }

        return $this->redirectToRoute('app_station_index', [], Response::HTTP_SEE_OTHER);
    }
    


    
}
