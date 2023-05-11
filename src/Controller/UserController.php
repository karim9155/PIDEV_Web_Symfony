<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Role;
use App\Form\RegisterType;
use App\Form\UserType;
use App\Form\AdminEditType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\UserStateRepository;
use App\services\imageUploader;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/user')]
class UserController extends AbstractController
{    

    
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository,imageUploader $imageUploader,UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user = new User();
        $user->setImage('null');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mdp = $form->get('password')->getData();
            $user->setPassword( $userPasswordEncoder->encodePassword(
                $user,
                $mdp
            ));
            /*$file=$form->get('images')->getData();
            if($file){
            $imageFileName = $imageUploader->upload($file);
            $user->setImage($imageFileName);*/
               /* $OriginalFile = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFile = $slugger->slug($OriginalFile);             
                $newFile=$safeFile.'-'.uniqid().'.'.$file->guessExtension();                ;
                $file->move(
                    $this->getParameter('image_directory'),$newFile);
                    $user->setImage($newFile);*/
                

            
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        
        ]);
    }
    #[Route('/register', name: 'app_user_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserRepository $userRepository,RoleRepository $roleRepository,UserStateRepository $userStateRepository,imageUploader $imageUploader): Response
    {
        $user = new User();
        $user->setIdRole($roleRepository->find(4));
        $user->setIdState($userStateRepository->find(2));
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $file=$form->get('images')->getData();
            if($file){
            $imageFileName = $imageUploader->upload($file);
            $user->setImage($imageFileName);

            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_register', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/register.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(AdminEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/admin/edit', name: 'app_user_admin', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, User $user, UserRepository $userRepository,imageUploader $imageUploader): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/delete', name: 'app_user_delete', methods: ['POST','GET'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
        }

    
