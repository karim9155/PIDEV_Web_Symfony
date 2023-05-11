<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Reservation;


#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/stats', name: 'app_reservation_stats')]
    public function stats(): Response
    {
        $reservations = $this->getDoctrine()
            ->getRepository(Reservation::class)
            ->findAll();

        // Count the number of reservations per day
        $stats = [];
        foreach ($reservations as $reservation) {
            $day = $reservation->getDateReservation()->format('Y-m-d');
            if (!isset($stats[$day])) {
                $stats[$day] = 0;
            }
            $stats[$day]++;
        }

        // Format the data for use by Chart.js
        $labels = array_keys($stats);
        $data = array_values($stats);

        // Render the chart using Twig
        return $this->render('dashboard/stats.html.twig', [
            'labels' => json_encode($labels),
            'data' => json_encode($data),
        ]);
    }
}
