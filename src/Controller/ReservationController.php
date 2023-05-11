<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reservation;
use App\Entity\Iteneraire;
use App\Entity\Ticket;
use App\Entity\MoyenTransport;
use App\Form\ReservationType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator, ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->orderByDate();
        $reservations = $reservationRepository->orderByDateAndTime();
        $pagination = $paginator->paginate(
            $reservations, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->render('reservation/index.html.twig', [
            'reservations' =>  $pagination
        ]);
    }

    #[Route('/getAllReservation', name: 'app_reservation_index_json', methods: ['GET'])]
    public function AllReservationJson(ReservationRepository $reservationRepository,NormalizerInterface $Normalizer): Response
    {
        $reservations = $reservationRepository->findAll();
        $data=[];
        foreach ($reservations as $reservartion) {
        $data[] = [
       'id' => $reservartion->getId(),
       'date_reservation' => $reservartion->getDateReservation(),
       'heure_depart' => $reservartion->getHeureDepart(),
       'heure_arrive' => $reservartion->getHeureArrive(),
       'status' => $reservartion->getStatus(),
       'type_ticket' => $reservartion->getTypeTicket(),
       'id_client' => $reservartion->getIdClient()->getNom() . ' ' . $reservartion->getIdClient()->getPrenom(),
       'id_moy' => $reservartion->getIdMoy()->getTypeVehicule(),
       'id_it' => $reservartion->getIdIt()->getPtsDepart(). ' ' . $reservartion->getIdIt()->getPtsArrive(),
        ];
        }
        return $this->json($data);
       
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository, MailerInterface $mailer,AuthenticationUtils $authenticationUtils,UserRepository $userRepository): Response
    //public function new(Request $request, ReservationRepository $reservationRepository, MailerInterface $mailer): Response

    {
        $reservation = new Reservation();
        //$reservation->setDateReservation(new \DateTime('now'));
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $user= new User();
            $error=$authenticationUtils->getLastAuthenticationError();
            $lastUsername=$authenticationUtils->getLastUsername();
            $user=$userRepository->findOneBy(['username'=>$lastUsername]);
            //$reservation->setHeureDepart($form->get('heure_depart')->getData());
            //$reservation->setHeureArrive($form->get('heure_arrive')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $reservation->setStatus("En attente");
            $entityManager->persist($reservation);
            $entityManager->flush();
            // Send email notification
            /*$email = (new Email())
            ->from('swift.transit2023@gmail.com')
            ->to('swift.transit2023@gmail.com')
            ->subject('New reservation added')
            ->html('<p>A new reservation has been added.</p>');
            $mailer->send($email);*/
            //$user = $this->getDoctrine()->getRepository(User::class)->find(1);
            $email = (new TemplatedEmail())

                ->from(Address::create('Swift Transit <TunisPublicTransport2023@hotmail.com>'))
                ->to($user->getEmail())
                ->subject('Reservation Information')
                ->text('Sending emails is fun again!')
                ->htmlTemplate('mailing/reservation.html.twig')
                ->context([
                    'reservation' => $reservation,
                    'user' => $user->getPrenom().' '.$user->getNom(),
                    'moyen' => $reservation->getIdMoy()->getTypeVehicule(), // add the moyen attribute
                    'heureDepart' => $reservation->getHeureDepart(), // add the heureDepart attribute
                    'heureArrivee' => $reservation->getHeureArrive(), // add the heureArrivee attribute
                    'typeTicket' => $reservation->getTypeTicket(), // add the status attribute
                    'itineraire' => $reservation->getIdIt()->getPtsDepart() . ' -> ' . $reservation->getIdIt()->getPtsArrive(), // add the itineraire attribute
                ]);
            $mailer->send($email);

            $this->addFlash('success', 'reservation ajouter avec succès!');
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

 //methode ajout reservation with json
 #[Route('/addReservationJSON/new', name: 'addReservationJSON', methods: ['GET', 'POST'])]
 public function addReservationJSON(Request $request, NormalizerInterface $normalizer , MailerInterface $mailer)

 {
    $em = $this->getDoctrine()->getManager();
    $reservation = new Reservation();    
    $date_reservation = new \DateTimeImmutable($request->get('date_reservation'));
    $reservation->setDateReservation($date_reservation);
   
    $heure_depart = new \DateTimeImmutable ($request->get('heure_depart'));
    $reservation->setHeureDepart($heure_depart);

    $heure_arrive = new \DateTimeImmutable($request->get('heure_depart'));
    $reservation->setHeureArrive($heure_arrive);

    $reservation->setTypeTicket($request->get('type_ticket'));
    $reservation->setStatus($request->get('status'));

    $id_client = $request->get('id_client');
    $user = $em->getRepository(User::class)->find($id_client);
    $reservation->setIdClient($user);

    $id_it = $request->get('id_it');
    $iteneraire = $em->getRepository(Iteneraire::class)->find($id_it);
    $reservation->setIdIt($iteneraire);

    $id_moy = $request->get('id_moy');
    $moyentransport = $em->getRepository(MoyenTransport::class)->find($id_moy);
    $reservation->setIdMoy($moyentransport);

    $em->persist($reservation);
    $em->flush();

    $email = (new TemplatedEmail())

    ->from(Address::create('Swift Transit <TunisPublicTransport2023@hotmail.com>'))
    ->to('<abir.machraoui@gmail.com>')
    ->subject('Reservation Information')
    ->text('Sending emails is fun again!')
    ->htmlTemplate('mailing/reservation.html.twig')
    ->context([
        'reservation' => $reservation,
        'user' => $user->getPrenom().' '.$user->getNom(),
        'moyen' => $reservation->getIdMoy()->getTypeVehicule(), // add the moyen attribute
        'heureDepart' => $reservation->getHeureDepart(), // add the heureDepart attribute
        'heureArrivee' => $reservation->getHeureArrive(), // add the heureArrivee attribute
        'typeTicket' => $reservation->getTypeTicket(), // add the status attribute
        'itineraire' => $reservation->getIdIt()->getPtsDepart() . ' -> ' . $reservation->getIdIt()->getPtsArrive(), // add the itineraire attribute
    ]);
   $mailer->send($email);


    $jsonContent= $normalizer->normalize($reservation, 'json',['groups'=>"reservations"]);
    return new Response("Reservation added succussfully" . json_encode($jsonContent));
 }


    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

   //methode show reservation par id json
    #[Route('/show/{id}', name: 'app_mobile_show', methods: ['GET'])]
    public function ReservationId($id,NormalizerInterface $normalizer,ReservationRepository $reservationRepository): Response
    {
        $reservation = $reservationRepository->find($id);
        $reservationNormalises= $normalizer->normalize($reservation, 'json',['groups'=>"reservations"]);
        return new Response(json_encode($reservationNormalises));    
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$reservationRepository->save($reservation, true);
            $entityManager->flush();
            $this->addFlash('success', 'reservation modifier avec succès!');
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

 //methode modif reservation with json
 #[Route('/updateReservationJSON/{id}', name: 'updateReservationJSON', methods: ['GET', 'POST'])]
 public function updateReservationJSON(Request $request, $id ,NormalizerInterface $normalizer )

 {
    $em = $this->getDoctrine()->getManager();
    $reservation = $em->getRepository(Reservation::class)->find($id);

    $date_reservation = new \DateTimeImmutable($request->get('date_reservation'));
    $reservation->setDateReservation($date_reservation);
   
    $heure_depart = new \DateTimeImmutable ($request->get('heure_depart'));
    $reservation->setHeureDepart($heure_depart);

    $heure_arrive = new \DateTimeImmutable($request->get('heure_depart'));
    $reservation->setHeureArrive($heure_arrive);

    $reservation->setTypeTicket($request->get('type_ticket'));
    $reservation->setStatus($request->get('status'));

    $id_client = $request->get('id_client');
    $user = $em->getRepository(User::class)->find($id_client);
    $reservation->setIdClient($user);

    $id_it = $request->get('id_it');
    $iteneraire = $em->getRepository(Iteneraire::class)->find($id_it);
    $reservation->setIdIt($iteneraire);

    $id_moy = $request->get('id_moy');
    $moyentransport = $em->getRepository(MoyenTransport::class)->find($id_moy);
    $reservation->setIdMoy($moyentransport);

    $em->flush();

    $jsonContent= $normalizer->normalize($reservation, 'json',['groups'=>"reservations"]);
    return new Response("Reservation updated successfully" . json_encode($jsonContent));
 }


    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
            //$entityManager->remove($reservation);
            //$entityManager->flush();
        }

        $this->addFlash('success', 'reservation supprimer avec succès!');
        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }

//method delete with json 
#[Route('/deleteReservationJSON/{id}', name: 'deleteReservationJSON')]
public function deleteReservationJSON($id,Request $request,NormalizerInterface $Normalizer)
{
    $em = $this->getDoctrine()->getManager();
    $Reservation = $em->getRepository(Reservation::class)->find($id);
        $em->remove($Reservation);
        $em->flush();
        $jsonContent= $Normalizer->normalize($Reservation, 'json',['groups'=>"reservations"]);
        return new Response("Reservation Deleted successfully".json_encode($jsonContent));
    } 

    //PDF Function
    #[Route('/pdf', name: 'ticket_pdf')]
    public function pdf(ReservationRepository $reservationRepository): Response
    {
        // Configuration de dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // initialisation pdf
        $dompdf = new Dompdf($pdfOptions);

        //retreive the events data from the database
        $reservation = $reservationRepository->findAll();
        $imgpath = file_get_contents('Back/img/ticket.jpg');
        $convert = base64_encode($imgpath);
        $img_src = 'data:image/jpeg;base64,' . $convert;

        $imgpath2 = file_get_contents('Back/img/swift.png');
        $convert2 = base64_encode($imgpath2);
        $img_src2 = 'data:image/png;base64,' . $convert2;

        //render the eventst from the database
        $html = $this->renderView('reservation/pdf.html.twig', [
            'reservations' => $reservation,
            'img_src' => $img_src,
            'img_src2' => $img_src2,

        ]);
        //load html
        $dompdf->loadHtml($html);

        //setup the paper format
        $dompdf->setPaper('A4', 'Portrait');

        //render pdf as html content
        $dompdf->render();


        //save pdf as listetickets pdf
        $pdfContent = $dompdf->output();

        // Create a response object
        $response = new Response();

        // Set the content type
        $response->headers->set('Content-Type', 'application/pdf');

        // Set the content of the response to the generated pdf content
        $response->setContent($pdfContent);

        // Set the content disposition header for file download
        $response->headers->set('Content-Disposition', 'attachment;filename="listetickets.pdf"');

        // Return the response object
        return $response;
    }
}
