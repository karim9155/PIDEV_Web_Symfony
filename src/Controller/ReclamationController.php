<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



class ReclamationController extends AbstractController
{

    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    // #[Route('/reclamations', name: 'app_reclamation')]
    // public function index(Request $request, PaginatorInterface $paginator): Response
    // {
    //     $entityManager = $this->getDoctrine()->getManager();
    //     $repository = $entityManager->getRepository(Reclamation::class);
    //     $query = $repository->createQueryBuilder('c')
    //         ->orderBy('c.id_reclamation', 'DESC');

        
    //     // $data = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
    //     // return $this->render('reclamation/index.html.twig', [
    //     //     'list' => $data  
    //     $data = $paginator->paginate(     ////jdeeed
    //         $query, // Requête contenant les données à paginer (ici notre requête custom)
    //         $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
    //         5 // Nombre de résultats par page
    //     );

    //     return $this->render('reclamation/index.html.twig', [
    //         'list' => $data /////jdeeed
    //     ]);
    // }

   /* #[Route('/reclamations', name: 'app_reclamation')]
public function index(Request $request, PaginatorInterface $paginator): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $repository = $entityManager->getRepository(Reclamation::class);
    $query = $repository->createQueryBuilder('c')
        ->orderBy('c.id_reclamation', 'DESC');

    // Get the value of the items per page from the request
    $itemsPerPage = $request->query->getInt('itemsPerPage', 5);

    $data = $paginator->paginate( 
        $query,
        $request->query->getInt('page', 1), 
        $itemsPerPage
    );

    // Create an array of available items per page options
    $availableItemsPerPage = [5, 10, 25, 50, 100];

    return $this->render('reclamation/index.html.twig', [
        'list' => $data,
        'itemsPerPage' => $itemsPerPage,
        'availableItemsPerPage' => $availableItemsPerPage,
    ]);
}*/
#[Route('/reclamations', name: 'app_reclamation')]
public function index(Request $request, PaginatorInterface $paginator,AuthenticationUtils $authenticationUtils,UserRepository $userRepository): Response
{
    $user= new User();
   
        $error=$authenticationUtils->getLastAuthenticationError();
        $lastUsername=$authenticationUtils->getLastUsername();
        $user=$userRepository->findOneBy(['username'=>$lastUsername]);
        $userId=$user->getId();
        //$userId = $_SESSION[$user->getId()];
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Reclamation::class);
        $query = $repository->createQueryBuilder('c')
            ->leftJoin('c.idUser', 'u')
            ->addSelect('u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('c.id_reclamation', 'DESC');
           
   
    $itemsPerPage = $request->query->getInt('itemsPerPage', 5);

    $data = $paginator->paginate( 
        $query,
        $request->query->getInt('page', 1), 
        $itemsPerPage
    );

    // Create an array of available items per page options
    $availableItemsPerPage = [5, 10, 25, 50, 100];

    return $this->render('reclamation/index.html.twig', [
        'list' => $data,
        'itemsPerPage' => $itemsPerPage,
        'availableItemsPerPage' => $availableItemsPerPage,
    ]);
}


    

    #[Route('/reclamation/add', name: 'add_reclamation')]
    public function addreclamation(ManagerRegistry $doctrine,Request $req, NotifierInterface $notifier,UserRepository $userRepository,AuthenticationUtils $authenticationUtils): Response {

        $badWords = ['merde','fuck','shit','con','connart','putain','pute','chier','bitch','bèullshit','bollocks','damn','putin'];
      
        $em = $doctrine->getManager();
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class,$reclamation);
        // cree une nouvelle formulaire pour recuperer les recs
        $form->handleRequest($req);
        $user = new User();
        

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $reclamation->getMessage_Rec();
            foreach ($badWords as $word) {
                if (stripos($text, $word) !== false) {
                    $this->addFlash('error', 'Le mot interdit "' . $word . '" a été trouvé dans le texte de la réclamation.');
                    return $this->redirectToRoute('add_reclamation');
                }
            }
           
        $lastUsername = $authenticationUtils->getLastUsername();
        $user= $userRepository->findOneBy(['username'=>$lastUsername]);
       // $id=1;
        //$utilisateur = $this->entityManager->getRepository(User::class)->find($id);
        $reclamation->setIdUser($user);
        $this->entityManager->persist($reclamation);
        // affecter le user au rec
        $this->entityManager->flush();
        // mise a jour

            $em->persist($reclamation);
            // affecter la reclamation kemla lel base
            $em->flush();
            // mise a jour lel bd
            return $this->redirectToRoute('app_reclamation');
        }

        return $this->renderForm('reclamation/ajouterreclamation.html.twig',['form'=>$form]);

}

   


#[Route('/reclamation/update/{id}', name: 'update_reclamation')]
    public function update(Request $req, $id,UserRepository $userRepository,AuthenticationUtils $authenticationUtils) {
      
      $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id); 
      $form = $this->createForm(ReclamationType::class,$reclamation);
      $form->handleRequest($req);
      $user=new User();
    if($form->isSubmitted() && $form->isValid()) {
       
   // $id=1;
       
    $lastUsername = $authenticationUtils->getLastUsername();
    $user= $userRepository->findOneBy(['username'=>$lastUsername]);
    //$utilisateur = $this->entityManager->getRepository(User::class)->find($id);
    $reclamation->setIdUser($user);
    $this->entityManager->persist($reclamation);
    $this->entityManager->flush();

    ////////////////////////////////////////////////////

        $em = $this->getDoctrine()->getManager();
        $em->persist($reclamation);
        $em->flush();


       
        return $this->redirectToRoute('app_reclamation');
    }

    return $this->renderForm('reclamation/modifierreclamation.html.twig',[
        'form'=>$form]);

}



#[Route('/reclamation/delete/{id}', name: 'delete_reclamation')]
public function delete($id) {
 
 
   
    $data = $this->getDoctrine()->getRepository(Reclamation::class)->find($id); 

      $em = $this->getDoctrine()->getManager();
      $em->remove($data);
      $em->flush();


     

      return $this->redirectToRoute('app_reclamation');
  }





  #[Route('/reclamation/pdf/{id}', name: 'app_pdfr')]
    public function pdf($id): Response
    {  
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('isRemoteEnabled', true);

        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);



        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reclamation/pdf.html.twig', [
            'reclamation' => [$reclamation]
        ]);


        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'landscape');


        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $output = $dompdf->output();
        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename=mypdf.pdf');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
/////////////////////////////////////mobile//////////////////////////////////
 // #[Route('/displayreclamation', name: 'displayreclamation')]
    //         public function displayreclamation(ReclamationRepository $repo, NormalizerInterface $normalizer)
    //         {
    //             $reclamations = $repo->findAll();
    //             $reclamationNormalized = $normalizer->normalize($reclamations, 'json', ['groups' => 'Reclamation']);
    //             return new JsonResponse($reclamationNormalized);
    //         }       


    //         #[Route('/addreclamationmobile', name: 'addreclamation')]
    //     public function new(ManagerRegistry $doctrine, Request $request)
    //     {
    //         $id=123;
    //         $entityManager = $doctrine->getManagerForClass(Reclamation::class);
    //         $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);

    //         $reclamation = new Reclamation();

    //         $reclamation->setIdUser($user);
    //         $reclamation->setMessageRec($request->get('message_rec'));
    //         $reclamation->setObjet($request->get('objet'));
    //         $reclamation->setStatut($request->get('statut'));
    //         //$reclamation->setDate_Rec($request->get('date_rec'));
    //         $dateString = $request->get('date_rec');
    //         $date = \DateTime::createFromFormat('Y-m-d', $dateString);
    //         $reclamation->setDate_Rec($date);
    //         $entityManager->persist($reclamation);
    //         $entityManager->flush();


    //         $serializer = new Serializer([new ObjectNormalizer()]);
    //         $formatted = $serializer->normalize($reclamation);
    //         return new JsonResponse($formatted);

    //     }

    //     #[Route('/reclamationdelete/{id}', name: 'delete_reclamation_Mobile')]
    //     public function deleteliv(ManagerRegistry $doctrine, $id): Response
    //     {
    //         $entityManager = $doctrine->getManagerForClass(Reclamation::class);
    //         $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);

    //         if ($reclamation != null) {
    //             $entityManager->remove($reclamation);
    //             $entityManager->flush();
    //             $serializer = new Serializer([new ObjectNormalizer()]);
    //             $formatted = $serializer->normalize("reclamation deleted succefully");
    //             return new JsonResponse($formatted);
    //         }
    //         return new JsonResponse("Category not found");
    //     }




    //     #[Route('/reclamationmodify/{id}', name: 'update_reclamation_mobile')]
    //     public function modify(ManagerRegistry $doctrine, Request $request, $id)
    //     {
    //         $entityManager = $doctrine->getManagerForClass(Reclamation::class);
    //         $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);


    //         $reclamation->settextrec($request->get('text_rec'));
    //         $reclamation->setsujet($request->get('sujet'));
    //         $entityManager->persist($reclamation);
    //         $entityManager->flush();


    //         $serializer = new Serializer([new ObjectNormalizer()]);
    //         $formatted = $serializer->normalize($request);
    //         return new JsonResponse($formatted);
    //     }

    #[Route('/displayreclamation', name: 'displayreclamation')]
    public function displayreclamation(ReclamationRepository $repo, NormalizerInterface $normalizer)
    {
        $reclamations = $repo->findAll();
        $reclamationNormalized = $normalizer->normalize($reclamations, 'json', ['groups' => 'Reclamation']);
        return new JsonResponse($reclamationNormalized);
    }


    #[Route('/addreclamationmobile', name: 'addreclamationmobile')]
    public function new(ManagerRegistry $doctrine, Request $request)
    {
        $id=1;  
        
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

       
        $entityManager = $doctrine->getManagerForClass(Reclamation::class);
        $reclamation = new Reclamation();

        //$userr=$user->serializer();
        $reclamation->setIdUser($user);
        $reclamation->setMessageRec($request->get('message_rec'));
        $reclamation->setObjet($request->get('objet'));
        $reclamation->setStatut($request->get('statut'));
        //$dateString = "1999-08-12";
        //$date = \DateTime::createFromFormat('Y-m-d', $dateString);
        //$reclamation->setDate_Rec($date);
        $dateString = $request->get('date_rec');
        $date = \DateTime::createFromFormat('Y-m-d', $dateString);
        $reclamation->setDate_Rec($date);
      
         

        
        $entityManager->persist($reclamation);
        $entityManager->flush();
        //////////////////mailinig///////////////////////
        //$reclamation = $this->entityManager->getRepository(Reclamation::class)->find($id);
            //$reclamation->setReclamation($reclamation);
            //$this->entityManager->persist($reponse);
            //$this->entityManager->flush();

            /////////////////////////////////////////////////////////////////

            //$em->persist($reponse);
            //$em->flush();

            $user = $reclamation->getIdUser();
            $prenom = $user->getPrenom();
            $nom = $user->getNom();
            $textrec = $reclamation->getMessage_Rec();
            //$textrep = $reponse->getTextRep();
            $emailc = $user->getEmail();



            /////////////////////////////////////////////////////////////////////////

             // Create a Transport object
        $transport = Transport::fromDsn('smtp://wassim.hassayoune@esprit.tn:hromnijnmbvxtlnq@smtp.gmail.com:465');

        // Create a Mailer object
        $mailer = new Mailer($transport);

        // Create an Email object
        $email = (new Email());

        // Set the "From address"
        $email->from('wassim.hassayoune@esprit.tn');

        // Set the "To address"
        $email->to(
            $emailc
        );



        // Set a "subject"
        $email->subject('Réclamation Traitée !');

        // Set the plain-text "Body"
        $email->text('Test Recu Mail.');

        // Set HTML "Body"
        $email->html('
        <div style="border:2px solid green; padding:20px; font-family: Arial, sans-serif;">
        <img src="http://localhost/PIDEV-VORTEX-WEB-Symfony-3A10/public/Back/img/swift.png" alt="My Image" class="logo">
          <h1 style="color:#006600; margin-top:0;">Bonjour ' . $nom . ' ' . $prenom . '</h1>  
          <p style="font-size:18px;">Site swifttransit vous remercie pour votre Réclamation.</p>
          <p style="font-size:18px;">Votre Réclamation ' . $textrec . ' a ete bien recu  </p>
          <div class="d-flex justify-content-center">
          <span class="bi bi-truck" style="font-size: 4rem;"></span>
        </div>
        
          <p style="font-size:18px;">Pour plus d\'informations, n\'hésitez pas à nous contacter.</p>
          <a href="#" style="display:inline-block; margin-top:20px; padding:10px 20px; background-color:#006600; color:#fff; text-decoration:none; border-radius:5px;">Nous contacter</a>
        </div>
      ');



        // Sending email with status
        try {
            // Send email
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }



        ///////////////////////mailing///////////////

       
       // $serializer = new Serializer([new ObjectNormalizer()]);
        //$formatted = $serializer->normalize($reclamation);
       // return new JsonResponse($formatted);
       
        $serializer = new Serializer([new ObjectNormalizer()]);
        $id= $reclamation->getIdUser()->getId();
        $formatted=['idUser'=>$id];
        $formatted = $serializer->normalize($formatted);
        return new JsonResponse($formatted);
    }



    #[Route('/reclamationdelete/{id}', name: 'delete_reclamation_Mobile')]
    public function deleteliv(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManagerForClass(Reclamation::class);
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);

        if ($reclamation != null) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize("reclamation deleted succefully");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("Category not found");
    }




    #[Route('/reclamationmodify/{id}', name: 'update_reclamation_mobile')]
    public function modify(ManagerRegistry $doctrine, Request $request, $id)
    {
        $entityManager = $doctrine->getManagerForClass(Reclamation::class);
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($id);


        //$reclamation->settextrec($request->get('text_rec'));
        //$reclamation->setsujet($request->get('sujet'));
        $reclamation->setMessageRec($request->get('message_rec'));
        $reclamation->setObjet($request->get('objet'));
        $reclamation->setStatut($request->get('statut'));
        //$request->get('date_rec');
        $dateString = $request->get('date_rec');
        $date = \DateTime::createFromFormat('Y-m-d', $dateString);
        $reclamation->setDate_Rec($date);
        
        $entityManager->persist($reclamation);
        $entityManager->flush();


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($request);
        return new JsonResponse($formatted);
    }


    #[Route('/reclamationser/{id}', name: 'reclamationser_mobile')]
    public function getReclamationById($id, ReclamationRepository $reclamationRepository, NormalizerInterface $normalizer): JsonResponse
    {
        $reclamation = $reclamationRepository->find($id);

        if (!$reclamation instanceof Reclamation) {
            return new JsonResponse(['error' => 'Reclamation not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $reclamationNormalized = $normalizer->normalize($reclamation, 'json', ['groups' => 'Reclamation']);
        return new JsonResponse($reclamationNormalized);
    }



    #[Route('/reclamationnonrepondu/{id}', name: 'reclamationser123_mobile')]
    public function getReclamationsNonReponse($id, ReclamationRepository $reclamationRepository, NormalizerInterface $normalizer): JsonResponse
    {
        $reclamation = $reclamationRepository->find($id);

        $reclamationNormalized = $normalizer->normalize($reclamation, 'json', ['groups' => 'Reclamation']);
        return new JsonResponse($reclamationNormalized);
    }

}
