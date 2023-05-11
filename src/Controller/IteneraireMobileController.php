<?php


namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

 use App\Entity\Iteneraire;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\JsonResponse;
 use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
 use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
//IteneraireRepository
use App\Repository\IteneraireRepository;
//response
use Symfony\Component\HttpFoundation\Response;


use Doctrine\Persistence\ManagerRegistry;

class IteneraireMobileController extends  AbstractController
{


    /******************Ajouter Reclamation*****************************************/
     /**
      * @Route("/addItineraire", name="add_itineraire")
      * @Method("POST")
      */

     public function ajouterItineraireAction(Request $request)
     {
         $itineraire = new Iteneraire();
         $pts_depart = $request->query->get("pts_depart");
         $pts_arrive = $request->query->get("pts_arrive");
        //  $description = $request->query->get("description");
        //  $objet = $request->query->get("objet");
          $em = $this->getDoctrine()->getManager();
        //  $date = new \DateTime('now');
        $itineraire->setPtsDepart($pts_depart);
        $itineraire->setPtsArrive($pts_arrive);

        //  $reclamation->setObjet($objet);
        //  $reclamation->setDescription($description);
        //  $reclamation->setDate($date);
        //  $reclamation->setEtat(0);

         $em->persist($itineraire);
         $em->flush();
         $serializer = new Serializer([new ObjectNormalizer()]);
         $formatted = $serializer->normalize($itineraire);
         return new JsonResponse($formatted);

     }

     /******************Supprimer Reclamation*****************************************/

     /**
      * @Route("/deleteIteneraire", name="delete_Iteneraire")
      * @Method("DELETE")
      */

     public function deleteIteneraireAction(Request $request) {
         $id = $request->get("id");

         $em = $this->getDoctrine()->getManager();
         $iteneraire = $em->getRepository(Iteneraire::class)->find($id);
         if($iteneraire!=null ) {
             $em->remove($iteneraire);
             $em->flush();

             $serialize = new Serializer([new ObjectNormalizer()]);
             $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
             return new JsonResponse($formatted);

         }
         return new JsonResponse("id reclamation invalide.");


     }

    /******************Modifier Reclamation*****************************************/
    /**
     * @Route("/updateIteneraire", name="update_Itenerairen")
     * @Method("PUT")
     */
    public function modifierIteneraireAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $itineraire = $this->getDoctrine()->getManager()
                        ->getRepository(Iteneraire::class)
                        ->find($request->get("id"));

        //$reclamation->setObjet($request->get("objet"));
        //$reclamation->setDescription($request->get("description"));
        $itineraire->setPtsDepart($request->get("pts_depart"));
        $itineraire->setPtsArrive($request->get("pts_arrive"));

        $em->persist($itineraire);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($itineraire);
        return new JsonResponse("Reclamation a ete modifiee avec success.");

    }



    /******************affichage Reclamation*****************************************/

     /**
      * @Route("/displayitineraire", name="display_itineraire")
      */
      public function getIteneraire(IteneraireRepository $repo, SerializerInterface $serializer)
      {
        $itineraire = $repo->findAll();
        

      
          //$json = $serializer->serialize($itineraire, 'json', ['groups' => 'itineraires']);
          $json = $serializer->serialize($itineraire,'json', ['groups' => "it"]);
          
          
          return new Response($json);
      }
    
    


     /******************Detail Reclamation*****************************************/

     /**
      * @Route("/detailReclamation", name="detail_reclamation")
      * @Method("GET")
      */

     //Detail Reclamation
     public function detailReclamationAction(Request $request)
     {
         $id = $request->get("id");

         $em = $this->getDoctrine()->getManager();
         $reclamation = $this->getDoctrine()->getManager()->getRepository(Reclamation::class)->find($id);
         $encoder = new JsonEncoder();
         $normalizer = new ObjectNormalizer();
         $normalizer->setCircularReferenceHandler(function ($object) {
             return $object->getDescription();
         });
         $serializer = new Serializer([$normalizer], [$encoder]);
         $formatted = $serializer->normalize($reclamation);
         return new JsonResponse($formatted);
     }


 }