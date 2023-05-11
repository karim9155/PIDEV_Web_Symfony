<?php

namespace App\Controller;
use App\services\Mailer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\UserStateRepository;
use App\services\imageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;

class SecurityController extends AbstractController
{
    #[Route('/inscription', name: 'security_registration',methods: ['GET', 'POST'])]
    public function registration(Request $request, UserRepository $userRepository,RoleRepository $roleRepository,UserStateRepository $userStateRepository,imageUploader $imageUploader,UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user = new User();
        $user->setIdRole($roleRepository->find(4));
        $user->setIdState($userStateRepository->find(2));
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mdp = $form->get('password')->getData();
            $user->setPassword( $userPasswordEncoder->encodePassword(
                $user,
                $mdp
            ));
            $file=$form->get('images')->getData();
            if($file){
            $imageFileName = $imageUploader->upload($file);
            $user->setImage($imageFileName);

            }
            $userRepository->save($user, true);

            return $this->redirectToRoute('security_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('security/registration.html.twig', [
            'controller_name' => 'RegistrationController',
            'user' => $user,
            'form' => $form->createView(),
                ]);
    }
    #[Route(path: '/connexion', name: 'security_login')]
    public function login(AuthenticationUtils $authenticationUtils,UserRepository $userRepository): Response
    {
        $user= new User();

        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $user= $userRepository->findOneBy(['username'=>$lastUsername]);
        /*if($user!=null){
        $mail= new Mailer($mailerInterface);
        $mail->sendEmail($user->getEmail());
    }*/
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route('/password', name: 'app_password_change')]
    public function changePasswordClient(RoleRepository $roleRepository, AuthenticationUtils $authenticationUtils,UserRepository $userRepository, Request $request,UserPasswordEncoderInterface $userPasswordEncoder,EntityManagerInterface $entityManager)
    {
        $user= new User();
        $lastUsername = $authenticationUtils->getLastUsername();
        $newPassword = $request->request->get('password');
        if($newPassword!=null){
        $user= $userRepository->findOneBy(['username'=>$lastUsername]);
        $user->setPassword($userPasswordEncoder->encodePassword($user,$newPassword));
        $userRepository->save($user, true);
        $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
        return $this->redirectToRoute('home');
        }
        return $this->render('Front/change.html.twig');
    }
    #[Route('/pass', name: 'app_password_admin')]
    public function changePasswordAdmin(RoleRepository $roleRepository, AuthenticationUtils $authenticationUtils,UserRepository $userRepository, Request $request,UserPasswordEncoderInterface $userPasswordEncoder,EntityManagerInterface $entityManager)
    {
        $user= new User();
        $lastUsername = $authenticationUtils->getLastUsername();
        $newPassword = $request->request->get('password');
        if($newPassword!=null){
        $user= $userRepository->findOneBy(['username'=>$lastUsername]);
        $user->setPassword($userPasswordEncoder->encodePassword($user,$newPassword));
        $userRepository->save($user, true);
        $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
        return $this->redirectToRoute('app_user_index');
        }
        return $this->render('user/change.html.twig');
    }
}
    

    