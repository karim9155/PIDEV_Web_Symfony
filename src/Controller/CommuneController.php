<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Form\CommuneType;
use App\Repository\CommuneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commune')]
class CommuneController extends AbstractController
{
    #[Route('/', name: 'app_commune_index', methods: ['GET'])]
    public function index(CommuneRepository $communeRepository): Response
    {
        return $this->render('commune/index.html.twig', [
            'communes' => $communeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commune_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommuneRepository $communeRepository): Response
    {
        $commune = new Commune();
        $form = $this->createForm(CommuneType::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $communeRepository->save($commune, true);

            return $this->redirectToRoute('app_commune_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commune/new.html.twig', [
            'commune' => $commune,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commune_show', methods: ['GET'])]
    public function show(Commune $commune): Response
    {
        return $this->render('commune/show.html.twig', [
            'commune' => $commune,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commune_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commune $commune, CommuneRepository $communeRepository): Response
    {
        $form = $this->createForm(CommuneType::class, $commune);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $communeRepository->save($commune, true);

            return $this->redirectToRoute('app_commune_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commune/edit.html.twig', [
            'commune' => $commune,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commune_delete', methods: ['POST'])]
    public function delete(Request $request, Commune $commune, CommuneRepository $communeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commune->getId(), $request->request->get('_token'))) {
            $communeRepository->remove($commune, true);
        }

        return $this->redirectToRoute('app_commune_index', [], Response::HTTP_SEE_OTHER);
    }
}
