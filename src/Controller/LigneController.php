<?php

namespace App\Controller;

use App\Entity\Ligne;
use App\Form\LigneType;
use App\Entity\MoyenTransport;
use App\Repository\LigneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Twilio\Rest\Client;



#[Route('/ligne')]
class LigneController extends AbstractController
{
    #[Route('/', name: 'app_ligne_index', methods: ['GET'])]
    public function index(LigneRepository $ligneRepository, PaginatorInterface $paginator ,  Request $request): Response
    {

        $ligs= $ligneRepository->findAll();
        $m = $paginator->paginate(
            $ligs, /* query NOT result */
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('ligne/index.html.twig', [
            'lignes' =>$m,
            
        ]);
    }

    #[Route('/new', name: 'app_ligne_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LigneRepository $ligneRepository): Response
    {
        $ligne = new Ligne();
        $form = $this->createForm(LigneType::class, $ligne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notifier = NotifierFactory::create();

            // Create your notification
             $notification =
                         (new Notification())
                         ->setTitle('Swift Transit')
                         ->setBody('Vous avez ajoutez une ligne')
                         ->setIcon(__DIR__.'/logo.png')
                         
      ;
      $notifier->send($notification);
            $ligneRepository->save($ligne, true);

            return $this->redirectToRoute('app_ligne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ligne/new.html.twig', [
            'ligne' => $ligne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ligne_show', methods: ['GET'])]
    public function show(Ligne $ligne): Response
    {
        return $this->render('ligne/show.html.twig', [
            'ligne' => $ligne,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ligne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ligne $ligne, LigneRepository $ligneRepository): Response
    {
        $form = $this->createForm(LigneType::class, $ligne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ligneRepository->save($ligne, true);

            return $this->redirectToRoute('app_ligne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ligne/edit.html.twig', [
            'ligne' => $ligne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ligne_delete', methods: ['POST'])]
    public function delete(Request $request, Ligne $ligne, LigneRepository $ligneRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligne->getId(), $request->request->get('_token'))) {
            $ligneRepository->remove($ligne, true);
        }

        return $this->redirectToRoute('app_ligne_index', [], Response::HTTP_SEE_OTHER);
    }

    //JSON time
#[Route("/ligne/AllLignes", name: "AllLignes")]
public function getLignes(LigneRepository $repo, SerializerInterface $serializer)
{
    $ligne = $repo->findAll();
    $json = $serializer->serialize($ligne, 'json', ['groups' => "ligne"]);
    return new Response($json);
}


#[Route("/ligne/addLigneJSON/new", name: "addLigneJSON")]
    public function addMoyJSON(Request $req,NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $ligne = new Ligne();
        $ligne->setNomLigne($req->get('nom_ligne'));
        $ligne->setTypeLigne($req->get('type_ligne'));
        $em->persist($ligne);
        $em->flush();

        $jsonContent = $Normalizer->normalize($ligne, 'json', ['groups' => 'ligne']);
        return new Response(json_encode($jsonContent));
    }

  

        #[Route("/ligne/deleteEventJSON/{id}", name: "deleteEventJSON")]
        public function deleteStudentJSON(Request $req, $id, NormalizerInterface $Normalizer)
        {
    
            $em = $this->getDoctrine()->getManager();
            $ligne = $em->getRepository(Ligne::class)->find($id);
            $em->remove($ligne);
            $em->flush();
            $jsonContent = $Normalizer->normalize($ligne, 'json', ['groups' => 'ligne']);
            return new Response("ligne deleted successfully " . json_encode($jsonContent));
        }

        #[Route('/ligne/updateEventJSON/{id}', name: "updateEventJSON")]
        public function updateLigneJSON(Request $req, $id, NormalizerInterface $Normalizer)
        {
    
            $em = $this->getDoctrine()->getManager();
            $ligne = $em->getRepository(Ligne::class)->find($id);
            $ligne->setNomLigne($req->get('nom_ligne'));
            $ligne->setTypeLigne($req->get('type_ligne'));
            $em->flush();
    
            $jsonContent = $Normalizer->normalize($ligne, 'json', ['groups' => 'ligne']);
            $sid    = "AC9a3661f4bb1dbf0ec9f8f5e02ff8a5d5";
            $token  = "3e0b0db115831dea895abd3850fa2d53";
            $client = new Client($sid,$token);
     
             $message = $client->messages
             ->create("whatsapp:+21628275170", // replace with admin's phone number
                 [
                   'from' => 'whatsapp:+14155238886', // replace with your Twilio phone number
                    'body' => 'New update has been done ' ,
                 ]
             );
            return new Response("Ligne updated successfully " . json_encode($jsonContent));
        }

       
    }







