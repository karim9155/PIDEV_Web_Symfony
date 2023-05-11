<?php


namespace App\Controller;

use App\Entity\Station;
use App\Entity\MoyenTransport;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\StationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StationRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;

#[Route('/Mobile')]
class StationMobileController extends AbstractController
{

#[Route('/afficheMobile', name: 'app_station_indexjson', methods: ['GET'])]
    public function indexjson(StationRepository $stationRepository,NormalizerInterface $normalizer): Response
    {
        $rec=$stationRepository->findAll() ;
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($rec);
        return new JsonResponse($formatted);

    }


    #[Route('/ajoutMobile', name: 'app_station_jsonAjouter', methods: ['GET','POST'])]
    public function indexjsonAjouter(Request $request)
    {
    $Station = new Station();
$long_alt = $request->query->get('long_alt');

$em = $this->getDoctrine()->getManager();

$Station->setLongAlt($long_alt);

$em-> persist($Station);
$em-> flush();


$serializer = new Serializer([new ObjectNormalizer()]);
$formatted = $serializer->normalize($Station);
return new JsonResponse($formatted);


    }
//dddddddddddddd
     #[Route('/deleteMobile', name: 'app_mobile_delete', methods: ['GET','DELETE'])]
    public function deleteMobile(Request $request)
    {
        $id=$request->get("id");
        $em = $this->getDoctrine()->getManager();
        $Station = $em->getRepository(Station::class)->find($id);
        if ($Station != null) {
            $em->remove($Station);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize("Station supprimée.");
            return new JsonResponse($formatted);

        } 
    } 

  

 /**
     * @Route("/updateMobile", name="update_station")
     * @Method("PUT")
     */
    public function modifierStation(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $station = $this->getDoctrine()->getManager()
                        ->getRepository(Station::class)
                        ->find($request->get("id"));

        $station->setLongAlt($request->get("long_alt"));

        $em->persist($station);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($station);
        return new JsonResponse("Station a ete modifiee avec success.");

    }




}