<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\services\Mailer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;





class ForgetPasswordController extends AbstractController
{
    private $code;

    private $user;
    #[Route('/forget', name: 'forget_password')]
    public function forget(Request $request, UserRepository $userRepository,MailerInterface $mailerInterface): Response
    {
        $email = $request->request->get('email');
        $user=$userRepository->findOneBy(['email'=>$email]);
        if($user!=null){
            $mail= new Mailer($mailerInterface);
            $cd=$this->generateCode();
            $session = $request->getSession();
            $session->set('code', $cd);
            $session->set('email', $email);
            $mail->sendEmail($user->getEmail(),$cd);
            $this->user=$user;
            return $this->redirectToRoute('rest_password_email_confirmation');
        }
        return $this->render('security/forget1.html.twig');
    }

   #[Route('/email', name: 'rest_password_email_confirmation')]
    public function verify(Request $request, UserRepository $userRepository,MailerInterface $mailerInterface,UrlGeneratorInterface $urlGenerator)
    {
        $codeInput = $request->request->get('code');
        if($codeInput!=null) {
            $session = $request->getSession();
            if($this->verifyCode($codeInput, $session->get('code'))){
                return $this->redirectToRoute('app_security_change');
                $this->addFlash('success', 'Votre code est correct');
            }else{
                $this->addFlash('fail', 'Votre code est invalide'); 
                return $this->redirectToRoute('rest_password_email_confirmation');
            }

        }
        return $this->render('security/forget2.html.twig');

    }

    #[Route('/code', name: 'app_security_change')]
    public function changePassword(RoleRepository $roleRepository, UserRepository $userRepository, Request $request,UserPasswordEncoderInterface $userPasswordEncoder,EntityManagerInterface $entityManager)
    {
        $newPassword = $request->request->get('password');
        if($newPassword!=null){
        $session = $request->getSession();
        $user=$userRepository->findOneBy(['email'=>$session->get('email')]);
        $user->setPassword($userPasswordEncoder->encodePassword($user,$newPassword));
        $userRepository->save($user, true);
        $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
        return $this->redirectToRoute('security_login');
            

        }
        return $this->render('security/change.html.twig');
    }

    public function generateCode(){
        $bytes = random_bytes(6);
        $code = bin2hex($bytes);
        return $code;
    }
    public function verifyCode(string $user_code,$code){
        if(strcmp($code,$user_code)==0){
            return true;
        }else{
            return false;
        }
    }
   
}
