<?php
namespace App\Controller;

use App\Entity\MoyenTransport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\MoyenTransportRepository;

#[Route('/moyen/transport')]
class statController extends AbstractController
{
   
    #[Route('/moyen/stat', name: 'app_stat', methods: ['GET'])]
    public function stat()
    {

        $repository = $this->getDoctrine()->getRepository(MoyenTransport::class);
        $moy= $repository->findAll();

        $em = $this->getDoctrine()->getManager();


        $t1 = 0;
        $t2 = 0;
        $t3=0;


        foreach ($moy as $moy) 
        {
           if ($moy->getTypeVehicule() =='Train')  
            {
                $t1 += 1;
            }
            elseif ($moy->getTypeVehicule() =='bus')
             {
                $t2 += 1;
             }
            else 
             {
                $t3 +=1;
             }
           

        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['Prix', 'Nom'],
                ['Train ', $t1],
                ['bus', $t2],
                ['metro', $t3]
            ]
        );
        $pieChart->getOptions()->setTitle('statistique a partir des moyens de transport');
        $pieChart->getOptions()->setHeight(1000);
        $pieChart->getOptions()->setWidth(1400);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('green');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(30);

       

        return $this->render('moyen_transport/index.html.twig', array('piechart' => $pieChart));

    }

}
