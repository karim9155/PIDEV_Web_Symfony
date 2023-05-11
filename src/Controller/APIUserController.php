<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Role;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\UserStateRepository;
use App\services\Mailer;
use phpDocumentor\Reflection\DocBlock\Tags\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use function PHPUnit\Framework\equalTo;

#[Route('/api', name: 'apiUser')]
class APIUserController extends AbstractController
{
    #[Route('/show', name: 'api_get_us', methods: ['GET','POST'])]
    public function show(Request $request,UserRepository $userRepository): JsonResponse
    {
       $user=$userRepository->findOneBy(['id'=> $request->get('id')]);
        if($user!=null){
            return $this->json($user->serializer());
        }
        return $this->json(['test'=>$request->get('id')]);
    }
    #[Route('/showAll', name: 'api_get_all', methods: ['GET','POST'])]
    public function showAll(UserRepository $userRepository): JsonResponse
   {
       $encoders = [ new JsonEncoder()];
       $normalizers = [new ObjectNormalizer()];

       $serializer = new Serializer($normalizers, $encoders);
      $users = $userRepository->findAll();
      $serializedUsers = [];
      foreach ($users as $user) {
       $serializedUsers[] = $user->serializer();
      }
     return $this->json($serializedUsers);
    }
   
    #[Route('/showR', name: 'api_get_r', methods: ['GET','POST'])]
    public function showR(RoleRepository $roleRepository,Request $request): JsonResponse
   {
    $role=$roleRepository->findOneBy(['id'=> $request->get('id')]);
    if($role!=null){
        return $this->json($role->serializer());
    }
    return $this->json(['test'=>$request->get('id')]);
    }
    #[Route('/login', name: 'api_login', methods: ['GET','POST'])]
    public function login(Request $request,UserRepository $userRepository)
    {
        $user = $this->getUser();
        $us= $userRepository->findOneBy(['username'=>$user->getUsername() ]);
        return $this->json($us->serializer()
        );
    }
    #[Route('/showRole', name: 'api_get_role', methods: ['GET','POST'])]
    public function showRole(RoleRepository $roleRepository): JsonResponse
   {
       $encoders = [ new JsonEncoder()];
       $normalizers = [new ObjectNormalizer()];

       $serializer = new Serializer($normalizers, $encoders);
      $roles = $roleRepository->findAll();
      $serializedRoles = [];
      foreach ($roles as $role) {
       $serializedRoles[] = $role->serializer();
      }
     return $this->json($serializedRoles);
    }
   
    #[Route('/edit', name: 'api_edit', methods: ['GET','POST'])]
    public function edit(Request $request, UserRepository $userRepository)
    {
        $user= $userRepository->findOneBy(['id'=>$request->get('id')]);
            if($request->get('nom')!=null){
                $user->setNom($request->get('nom'));
            }
            if($request->get('prenom')!=null){
                $user->setPrenom($request->get('prenom'));
            }
            if($request->get('username')!=null) {
                $user->setUsername($request->get('username'));
            }
            if($request->get('email')!=null) {
                $user->setEmail($request->get('email'));
            }
            if($request->get('num_tel')!=null) {
                $user->setNumTel((int)$request->get('num_tel'));
            }
            if($request->get('cin')!=null) {
                $user->setCIN((int)$request->get('cin'));
            }
            $userRepository->save($user,true);

            return $this->json(['response'=> 'success']);
    }

    #[Route('/delete', name: 'api_delete', methods: ['GET','POST'])]
    public function delete(Request $request, UserRepository $userRepository)
    {

            $user= $userRepository->findOneBy(['id'=>$request->get('id')]);
            if($user!=null){
                $userRepository->remove($user, true);
                return $this->json(['response'=> 'success']);
            }
            else
                return $this->json(['response'=> 'failed']);
    }
    #[Route('/add', name: 'api_add', methods: ['GET','POST'])]
    public function create(UserPasswordEncoderInterface $userPasswordEncoder ,Request $request, UserRepository $userRepository,RoleRepository $roleRepository,UserStateRepository $userStateRepository)
    {
        $user = new User();
        if($request->get('nom')!=null){
            $user->setNom($request->get('nom'));
        }
        if($request->get('prenom')!=null){
            $user->setPrenom($request->get('prenom'));
        }
        if($request->get('username')!=null) {
            $user->setUsername($request->get('username'));
        }
        if($request->get('email')!=null) {
            $user->setEmail($request->get('email'));
        }
        if($request->get('num_tel')!=null) {
            $user->setNumTel((int)$request->get('num_tel'));
        }
        if($request->get('cin')!=null) {
            $user->setCIN((int)$request->get('cin'));
        }
        if($request->get('mdp')!=null) {
            $user->setPassword($userPasswordEncoder->encodePassword(
                $user,
                $request->get('mdp'))
            );
        }

        $user->setIdState($userStateRepository->findOneBy(['id'=>2]));
        $user->setIdRole($roleRepository->findOneBy(['id'=>4]));
        $userRepository->save($user,true);

        return $this->json(['response'=> 'success']);
    }
    #[Route('/addAdmin', name: 'api_add_admin', methods: ['GET','POST'])]
    public function createAdmin(UserPasswordEncoderInterface $userPasswordEncoder ,Request $request, UserRepository $userRepository,RoleRepository $roleRepository,UserStateRepository $userStateRepository)
    {
        $user = new User();
        if($request->get('nom')!=null){
            $user->setNom($request->get('nom'));
        }
        if($request->get('prenom')!=null){
            $user->setPrenom($request->get('prenom'));
        }
        if($request->get('username')!=null) {
            $user->setUsername($request->get('username'));
        }
        if($request->get('email')!=null) {
            $user->setEmail($request->get('email'));
        }
        if($request->get('num_tel')!=null) {
            $user->setNumTel((int)$request->get('num_tel'));
        }
        if($request->get('cin')!=null) {
            $user->setCIN((int)$request->get('cin'));
        }
        if($request->get('mdp')!=null) {
            $user->setPassword($userPasswordEncoder->encodePassword(
                $user,
                $request->get('mdp'))
            );
        }
        if($request->get('id_role')!=null){
            $user->setIdRole($roleRepository->findOneBy(['id'=>$request->get('id_role')]));
        }

        $user->setIdState($userStateRepository->findOneBy(['id'=>2]));
        //$user->setIdRole($roleRepository->findOneBy(['id'=>4]));
        $userRepository->save($user,true);

        return $this->json(['response'=> 'success']);
    }
    #[Route('/email', name: 'api_rest',methods: ["POST"])]
    public function forget(Request $request, UserRepository $userRepository,MailerInterface $mailerInterface)
    {
        $email = $request->get('email');
       // return $this->json($email);
        $user=$userRepository->findOneBy(
            ['email'=>$email]);
        if($user!=null){
            $mail= new Mailer($mailerInterface);
            $cd=$this->generateCode();
            $mail->sendEmail($user->getEmail(),$cd);
            return $this->json(["code"=> $cd,"id"=>$user->getId()]);
        }
        return $this->json(["error"=>"error"]);
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
    #[Route('/change', name: 'api_change')]
    public function changePassword(RoleRepository $roleRepository, UserRepository $userRepository, Request $request,UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $user= $userRepository->findOneBy(['id'=>$request->get('id')]);
        if($request->get('mdp')!=null) {
            $user->setPassword($userPasswordEncoder->encodePassword(
                $user,
                $request->get('mdp'))
            );
        }
        $userRepository->save($user, true);
       return $this->json(['response'=> 'success']);
            

    }

}
