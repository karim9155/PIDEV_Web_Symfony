<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search_name')]
    public function index(LoggerInterface $logger,Request $request, UserRepository $userRepository,EntityManagerInterface $entityManager): Response
    {

        if ($request->isXmlHttpRequest()){
            $logger->critical('I left the oven on!', [
                // include extra "context" info in your logs
                'cause' => $request->get('search'),
            ]);
            $results = $userRepository->LikeUserName($request->get('search'));
            $encoders = [new JsonEncoder()];
            $serializer = new Serializer([new ObjectNormalizer()],$encoders);
            foreach ($results as $result){
                $users[]=$serializer->serialize($result,'json',[AbstractNormalizer::ATTRIBUTES => ['id','nom','prenom','username','email','num_tel','image','CIN', 'id_role' => ['nom'],'id_state' =>['status']]]);
            }
            return new JsonResponse([
                'users' => $users
            ]);
        }
        else{
            return new JsonResponse([
                'users' => 2
            ]);
        }
    }


}
