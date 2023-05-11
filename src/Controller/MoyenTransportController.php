<?php

namespace App\Controller;

use App\Entity\MoyenTransport;
use App\Form\MoyenTransportType;
use App\Repository\MoyenTransportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dompdf\Dompdf;
use Knp\Snappy\Pdf;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Twilio\Rest\Client;
use Dompdf\Options;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;




#[Route('/moyen/transport')]
class MoyenTransportController extends AbstractController
{
    #[Route('/', name: 'app_moyen_transport_index', methods: ['GET'])]
    public function index(MoyenTransportRepository $moyenTransportRepository , PaginatorInterface $paginator ,  Request $request): Response
    {
        $moys= $moyenTransportRepository->findAll();
        $m = $paginator->paginate(
            $moys, /* query NOT result */
            $request->query->getInt('page', 1),
            4
        );
    $currentSort = $request->query->get('sort', 'ASC');
    if ($currentSort == 'ASC') {
        $moys = $moyenTransportRepository->findBy([], ['marque' => 'ASC']);
        $nextSort = 'DESC';
    } else {
        $moys = $moyenTransportRepository->findBy([], ['marque' => 'DESC']);
        $nextSort = 'ASC';
    }
    
    
        $data = [];
        $totalsByCategory = [];
        // Loop over each service and group them by category, calculating the total price for each category
        foreach ($moys as $moyen) {
            $type = $moyen->getTypeVehicule();
            if (!isset($totalsByCategory[$type])) {
                $totalsByCategory[$type] = 0;
            }

            $totalsByCategory[$type] +=1;
        }

        // Sort the categories alphabetically
        ksort($totalsByCategory);
             //Stata
        // Extract the category names and totals
        $labels = [];
        $data = [];

        foreach ($totalsByCategory as $type => $total) {
            $labels[] = $type;
            $data[] = $total;
        }
        return $this->render('moyen_transport/index.html.twig',
            array('moyen_transports' => $m,
                'chartData' => json_encode($data),
                'nextSort' => $nextSort,
                  'sort_by' => 'marque',
        ));
    }

    #[Route('/new', name: 'app_moyen_transport_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MoyenTransportRepository $moyenTransportRepository): Response
    {
        $moyenTransport = new MoyenTransport();
        $form = $this->createForm(MoyenTransportType::class, $moyenTransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notifier = NotifierFactory::create();

      // Create your notification
       $notification =
                   (new Notification())
                   ->setTitle('Swift Transit')
                   ->setBody('Vous avez ajoutez un moyen de transport')
                   ->setIcon(__DIR__.'/logo.png')
                  
;

// Send it
$notifier->send($notification);
            $moyenTransportRepository->save($moyenTransport, true);

            return $this->redirectToRoute('app_moyen_transport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('moyen_transport/new.html.twig', [
            'moyen_transport' => $moyenTransport,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_moyen_transport_show', methods: ['GET'])]
    public function show(MoyenTransport $moyenTransport): Response
    {
        return $this->render('moyen_transport/show.html.twig', [
            'moyen_transport' => $moyenTransport,
        ]);
    }

   /*  #[Route('/{id}/edit', name: 'app_moyen_transport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MoyenTransport $moyenTransport, MoyenTransportRepository $moyenTransportRepository): Response
    {
        $form = $this->createForm(MoyenTransportType::class, $moyenTransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $moyenTransportRepository->save($moyenTransport, true);

            return $this->redirectToRoute('app_moyen_transport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('moyen_transport/edit.html.twig', [
            'moyen_transport' => $moyenTransport,
            'form' => $form,
        ]);
    } */


    #[Route('/{id}/edit', name: 'app_moyen_transport_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MoyenTransport $moyenTransport, MoyenTransportRepository $moyenTransportRepository): Response
    {
        $form = $this->createForm(MoyenTransportType::class, $moyenTransport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $moyenTransportRepository->save($moyenTransport, true);
            if($moyenTransport->getEtat() == 'en panne'){
               // $sid    = "AC77d09a6df0101ea39b08e6549074c1dc";
                //$token  = "e5672d5f70f42a9477e217a1c9fbafb8";
                $sid    = "AC9a3661f4bb1dbf0ec9f8f5e02ff8a5d5";
                $token  = "3e0b0db115831dea895abd3850fa2d53";
            $twilio = new Client($sid, $token);

    $message = $twilio->messages
      ->create("whatsapp:+21628275170", // to
        [
          //'from' => "whatsapp:+14155238886",
          'from' => "whatsapp:+14155238886",
          'body' => 'Vous avez un moyen de transport id: ' .$moyenTransport->getId() .' est '. $form->get('etat')->getData(),
        ]
      );
    }
        
            return $this->redirectToRoute('app_moyen_transport_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('moyen_transport/edit.html.twig', [
            'moyen_transport' => $moyenTransport,
            'form' => $form,
        ]);
    }





    #[Route('/{id}', name: 'app_moyen_transport_delete', methods: ['POST'])]
    public function delete(Request $request, MoyenTransport $moyenTransport, MoyenTransportRepository $moyenTransportRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$moyenTransport->getId(), $request->request->get('_token'))) {
            $moyenTransportRepository->remove($moyenTransport, true);
        }

        return $this->redirectToRoute('app_moyen_transport_index', [], Response::HTTP_SEE_OTHER);
    }

    
    #[Route('/searchMoy', name: 'searchMoy')]
    public function searchMoy(Request $request, NormalizerInterface $Normalizer , MoyenTransportRepository $mr): Response
    {

        $repository=$this->getDoctrine()->getRepository(MoyenTransport::class);
        $requestString=$request->get('searchValue');
        $moyen=$mr->findmoybyid($requestString);
        $jsonContent=$Normalizer->normalize($moyen,'json',['groups'=>'moyens']);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }



    
    #[Route('/moys', name: 'app_moys')]
    public function listMoys(Request $request,MoyenTransportRepository $repository,PaginatorInterface $paginator): Response
    {
        $moys= $repository->findAll();
        $moys = $paginator->paginate(
            $moys, /* query NOT result */
            $request->query->getInt('page', 1),
            4
        );

        return $this->render("moyen_transport/index.html.twig",array("moyen_transports"=>$moys));
    }
      
    #[Route('/moyen/stat', name: 'stat_moy', methods: ['GET'])]
    
    public function stats(ManagerRegistry $doctrine): Response
    {
        $repository = $this->getDoctrine()->getRepository(MoyenTransport::class);
        $moyens = $repository->findAll();
        $em=$doctrine->getManager();
        $types = $em->getRepository(MoyenTransport::class)->countByTypeVehicule();

        return $this->render('stats.html.twig', [
            'types' => $types
        ]);
    }





 #[Route("/moyen/search", name: "moyen_search")]

public function search(Request $request)
{
    $form = $this->createFormBuilder()
        ->add('marque', TextType::class)
        ->getForm();

    $moys = [];
    if ($request->isMethod('POST')) {
        $marque = $request->request->get('form')['marque'];
        $moys = $this->getDoctrine()
            ->getRepository(MoyenTransport::class)
            ->findByMarque($marque);
    }

    return $this->render('moyen_transport/search.html.twig', [
        'form' => $form->createView(),
        'moys' => $moys,
    ]);
}
#[Route('/moyen/searchm', name: 'search_marque')]
public function searchMarque(Request $request, MoyenTransportRepository $repository): JsonResponse
    {
        $marque = $request->request->get('marque');

        $result = $repository->findByMarque($marque);

        return new JsonResponse($result);
    }

//JSON time
#[Route("/moyen/AllMoys", name: "list")]

public function getMoys(MoyenTransportRepository $repo, SerializerInterface $serializer)
{
    $moys = $repo->findAll();
    $json = $serializer->serialize($moys, 'json', ['groups' => "moys"]);
    return new Response($json);
}


#[Route("/moyen/addMoyJSON/new", name: "addMoyJSON")]
    public function addMoyJSON(Request $req,NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $moys = new MoyenTransport();
        $moys->setMatricule($req->get('matricule'));
        $moys->setNum($req->get('num'));
        $moys->setCapacite($req->get('capacite'));
        $moys->setTypeVehicule($req->get('type_vehicule'));
        $moys->setMarque($req->get('marque'));
        $moys->setEtat($req->get('etat'));
        $moys->setIdLigne($req->get('id_ligne_id'));
        $moys->setStation($req->get('station_id'));
        $em->persist($evenement);
        $em->flush();

        $jsonContent = $Normalizer->normalize($evenement, 'json', ['groups' => 'moys']);
        return new Response(json_encode($jsonContent));
    }



}


