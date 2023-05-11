<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/Mobile')]
class ReservationMobileController extends AbstractController
{
    /*#[Route('/getAllReservation', name: 'app_reservation_index_json', methods: ['GET'])]
    public function AllReservationJson(ReservationRepository $reservationRepository,SerializerInterface $serializer): Response
    {
        $reservations = $reservationRepository->findAll();
        $json= $serializer->serialize($reservations, 'json',['groups'=>"reservations"]);
        return new Response($json);
       
    }*/


   /* #[Route('/ajoutMobile', name: 'app_reservation_jsonAjouter', methods: ['GET','POST'])]
    public function indexjsonAjouter(Request $request)
    {
    $Reservation = new Reservation();
$long_alt = $request->query->get('long_alt');

$em = $this->getDoctrine()->getManager();

$Reservation->setLongAlt($long_alt);

$em-> persist($Reservation);
$em-> flush();
$serializer = new Serializer([new ObjectNormalizer()]);
$formatted = $serializer->normalize($Reservation);
return new JsonResponse($formatted);


    }

     #[Route('/deleteMobile', name: 'app_reservation_delete', methods: ['GET','DELETE'])]
    public function deleteMobile(Request $request)
    {
        $id=$request->get("id");
        $em = $this->getDoctrine()->getManager();
        $Reservation = $em->getRepository(Reservation::class)->find($id);
        if ($Reservation != null) {
            $em->remove($Reservation);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize("Reservation supprimée.");
            return new JsonResponse($formatted);

        } 
    } */

 /**
     * @Route("/updateMobile", name="update_station")
     * @Method("PUT")
     */
    public function modifierReservation(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $reservation = $this->getDoctrine()->getManager()
                        ->getRepository(Reservation::class)
                        ->find($request->get("id"));

        $reservation->setLongAlt($request->get("long_alt"));
        $em->persist($reservation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reservation);
        return new JsonResponse("Votre Reservation est modifiee avec success.");

    }
}
