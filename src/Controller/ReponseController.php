<?php

namespace App\Controller;


use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Entity\Reclamation;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use App\Repository\ReponseRepository;
use Symfony\Component\Mailer\Transport;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;


class ReponseController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/reponse/reclamation', name: 'app_reclamation_reponse')]
    public function indexreclamation(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reclamationRepository = $entityManager->getRepository(Reclamation::class);
        $queryBuilder = $reclamationRepository->createQueryBuilder('rec');
        
        $query = $reclamationRepository->createQueryBuilder('rec')
            ->leftJoin('rec.reponse', 'rep')
            ->where('rep.id_reponse IS NULL')
            ->getQuery();
        
        $ReclamationNOTReponse  = $query->getResult();
        

        return $this->render('reponse/reclamation.html.twig', [
            'list' => $ReclamationNOTReponse
        ]);
    }


    #[Route('/reponse', name: 'app_reponse')]
    public function indexreponse(Request $request, PaginatorInterface $paginator): Response
    {
        $data = $this->getDoctrine()->getRepository(Reponse::class)->findAll();
        $data = $paginator->paginate(
            $data, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('reponse/reponse.html.twig', [
            'list' => $data 
]);
    }



    #[Route('/reponse/add/{id}', name: 'add_reponse')]
    public function addreponse($id,ManagerRegistry $doctrine,Request $req): Response {
    {
        
        $em = $doctrine->getManager();
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class,$reponse);
        $form->handleRequest($req);
        

        if($form->isSubmitted() && $form->isValid()) {
            $reclamation = $this->entityManager->getRepository(Reclamation::class)->find($id);
            $reponse->setReclamation($reclamation);
            $this->entityManager->persist($reponse);
            $this->entityManager->flush();

            /////////////////////////////////////////////////////////////////

            $em->persist($reponse);
            $em->flush();

            $user = $reclamation->getIdUser();
            $prenom = $user->getPrenom();
            $nom = $user->getNom();
            $textrec = $reclamation->getMessage_Rec();
            $textrep = $reponse->getTextRep();
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
          <p style="font-size:18px;">Votre Réclamation ' . $textrec . ' a été repondu par : ' . $textrep .  ' </p>
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






            return $this->redirectToRoute('app_reponse');
        }

        return $this->renderForm('reponse/ajouterreponse.html.twig',['form'=>$form]);
    }
}




#[Route('/reponse/update/{id}', name: 'update_reponse')]
    public function update(Request $req, $id) {
      
      $reponse = $this->getDoctrine()->getRepository(Reponse::class)->find($id); 
      $form = $this->createForm(ReponseType::class,$reponse);
      $form->handleRequest($req);
    if($form->isSubmitted() && $form->isValid()) {
       


        $em = $this->getDoctrine()->getManager();
        $em->persist($reponse);
        $em->flush();


        return $this->redirectToRoute('app_reponse');
    }

    return $this->renderForm('reponse/modifierreponse.html.twig',[
        'form'=>$form]);

}





#[Route('/reponse/delete/{id}', name: 'delete_reponse')]
public function delete($id) {
 
 
   
    $data = $this->getDoctrine()->getRepository(Reponse::class)->find($id); 

      $em = $this->getDoctrine()->getManager();
      $em->remove($data);
      $em->flush();


 

      return $this->redirectToRoute('app_reponse');
  }





  #[Route('/reponse/stat', name: 'stat')]
  public function statistiques(){
      // On va chercher toutes les réclamations
      $rep = $this->getDoctrine()->getRepository(Reclamation::class);
      $reclamations = $rep->findAll();
      
      // On initialise un tableau pour chaque sujet
      $sujets = [
          'infrastructure' => 0,
          'conducteur' => 0,
          'retard' => 0,
          'Autres' => 0
      ];
      
      // On parcourt toutes les réclamations et on incrémente le compteur pour chaque sujet
      foreach($reclamations as $reclamation){
          switch($reclamation->getObjet()){
              case 'infrastructure':
                  $sujets['infrastructure']++;
                  break;
              case 'conducteur':
                  $sujets['conducteur']++;
                  break;
              case 'retard':
                  $sujets['retard']++;
                  break;
              default:
                  $sujets['Autres']++;
                  // On ne fait rien pour les réclamations avec un sujet non reconnu
          }
      }
      
      // On convertit le tableau en format JSON pour pouvoir l'utiliser avec Chart.js
      $sujetsJson = json_encode(array_values($sujets));
      
      return $this->render('reponse/stat.html.twig', [
          'Sujets' => $sujetsJson
      ]);
  }

  /////////////////mobile////////////////////
  
#[Route('/displayreponse', name: 'displayreponse')]
public function displayreclamation(ReponseRepository $repo, NormalizerInterface $normalizer)
{
    $reponses = $repo->findAll();
    
    $reponseNormalized = [];
    
    foreach ($reponses as $reponse) {
        $reclamationId = $reponse->getReclamation()->getIdReclamation();
        $reponseNormalized[] = [
            'id_reponse' => $reponse->getIdReponse(),
            'text_rep' => $reponse->getTextRep(),
            'id_reclamation' => $reclamationId
        ];
    }
    
    return new JsonResponse($reponseNormalized);
} 

    


#[Route('/addreponse', name: 'addreponse')]
public function new(ManagerRegistry $doctrine, Request $request)
{

$entityManager = $doctrine->getManagerForClass(Reponse::class);


$reponse = new Reponse();


$reponse->setText_Rep($request->get('text_rep'));
$id = intval($request->get('id_reclamation'));


$recla = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
$reponse->setReclamation($recla);

$entityManager->persist($reponse);
$entityManager->flush();


$serializer = new Serializer([new ObjectNormalizer()]);
$formatted = $serializer->normalize($reponse);
return new JsonResponse($formatted);

}
   

#[Route('/reponsedelete/{id}', name: 'delete_reponse_Mobile')]
public function deleteliv(ManagerRegistry $doctrine, $id): Response
{
$entityManager = $doctrine->getManagerForClass(Reponse::class);
$rep = $entityManager->getRepository(Reponse::class)->find($id);

if ($rep != null) {
    $entityManager->remove($rep);
    $entityManager->flush();
    $serializer = new Serializer([new ObjectNormalizer()]);
    $formatted = $serializer->normalize("rep deleted succefully");
    return new JsonResponse($formatted);
}
return new JsonResponse("rep not found");
}




#[Route('/reponsemodify/{id}', name: 'update_reponse_mobile')]
public function modify(ManagerRegistry $doctrine, Request $request, $id)
{
$entityManager = $doctrine->getManagerForClass(Reponse::class);



$reponse = $entityManager->getRepository(Reponse::class)->find($id);
$reponse->setText_Rep($request->get('text_rep'));

$entityManager->persist($reponse);
$entityManager->flush();


$serializer = new Serializer([new ObjectNormalizer()]);
$formatted = $serializer->normalize($request);
return new JsonResponse($formatted);
}

}
