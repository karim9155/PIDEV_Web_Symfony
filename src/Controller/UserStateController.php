<?php

namespace App\Controller;

use App\Entity\UserState;
use App\Form\UserStateType;
use App\Repository\UserStateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/userstate')]
class UserStateController extends AbstractController
{
    #[Route('/', name: 'app_user_state_index', methods: ['GET'])]
    public function index(UserStateRepository $userStateRepository): Response
    {
        return $this->render('user_state/index.html.twig', [
            'user_states' => $userStateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_state_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserStateRepository $userStateRepository): Response
    {
        $userState = new UserState();
        $form = $this->createForm(UserStateType::class, $userState);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userStateRepository->save($userState, true);

            return $this->redirectToRoute('app_user_state_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_state/new.html.twig', [
            'user_state' => $userState,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_state_show', methods: ['GET'])]
    public function show(UserState $userState): Response
    {
        return $this->render('user_state/show.html.twig', [
            'user_state' => $userState,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_state_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserState $userState, UserStateRepository $userStateRepository): Response
    {
        $form = $this->createForm(UserStateType::class, $userState);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userStateRepository->save($userState, true);

            return $this->redirectToRoute('app_user_state_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_state/edit.html.twig', [
            'user_state' => $userState,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_state_delete', methods: ['POST'])]
    public function delete(Request $request, UserState $userState, UserStateRepository $userStateRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userState->getId(), $request->request->get('_token'))) {
            $userStateRepository->remove($userState, true);
        }

        return $this->redirectToRoute('app_user_state_index', [], Response::HTTP_SEE_OTHER);
    }
}
