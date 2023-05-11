<?php

namespace App\Controller;

use App\Entity\Iteneraire;
use App\Form\IteneraireType;
use App\Repository\IteneraireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/iteneraire')]
class IteneraireController extends AbstractController
{
    #[Route('/', name: 'app_iteneraire_index', methods: ['GET'])]
    public function index(Request $request ,IteneraireRepository $iteneraireRepository): Response
    {
        $searchTerm = $request->query->get('q');
        $iteneraires = $this->getDoctrine()->getRepository(Iteneraire::class)->findBySearchTerm($searchTerm);
        return $this->render('iteneraire/index.html.twig', [
            'iteneraires' => $iteneraires,
            'searchTerm' => $searchTerm,
            
        ]);
    }

    #[Route('/new', name: 'app_iteneraire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IteneraireRepository $iteneraireRepository): Response
    {
        $iteneraire = new Iteneraire();
        $form = $this->createForm(IteneraireType::class, $iteneraire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $iteneraireRepository->save($iteneraire, true);

            return $this->redirectToRoute('app_iteneraire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('iteneraire/new.html.twig', [
            'iteneraire' => $iteneraire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_iteneraire_show', methods: ['GET'])]
    public function show(Iteneraire $iteneraire): Response
    {
        return $this->render('iteneraire/show.html.twig', [
            'iteneraire' => $iteneraire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_iteneraire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Iteneraire $iteneraire, IteneraireRepository $iteneraireRepository): Response
    {
        $form = $this->createForm(IteneraireType::class, $iteneraire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $iteneraireRepository->save($iteneraire, true);

            return $this->redirectToRoute('app_iteneraire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('iteneraire/edit.html.twig', [
            'iteneraire' => $iteneraire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_iteneraire_delete', methods: ['POST'])]
    public function delete(Request $request, Iteneraire $iteneraire, IteneraireRepository $iteneraireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$iteneraire->getId(), $request->request->get('_token'))) {
            $iteneraireRepository->remove($iteneraire, true);
        }

        return $this->redirectToRoute('app_iteneraire_index', [], Response::HTTP_SEE_OTHER);
    }
}
