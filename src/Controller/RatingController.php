<?php
namespace App\Controller;

use App\Entity\MoyenTransport;
use App\Form\MoyenTransportType;
use App\Repository\MoyenTransportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Dompdf\Dompdf;
use Knp\Snappy\Pdf;
use Dompdf\Options;

#[Route('/moyen/transport')]
class RatingController extends AbstractController
{

    
}