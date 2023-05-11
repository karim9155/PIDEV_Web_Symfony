<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/moyen/transport')]
class BotController extends AbstractController
{
    #[Route('/lignes/bot', name: 'app_bot')]
    public function index(Request $request): Response
    {
       $a = "fdg";     
    $qa = [
        'Bonjour' => 'Bonjour ! Comment puis-je vous aider ?',
        'merci' => 'Bienvenu',
        'qui vous etes' => 'je suis le bot de swift Transit',
        'Quels sont les types des moyens de transport' => 'Il y a 3 : BUS , Metro , Train ',
        'quels sont les types des moyens de transport' => 'Il y a 3 : BUS , Metro , Train ',

        'Quel temps fait-il aujourd\'hui ?' => 'Je suis désolé, je ne suis pas capable de répondre à cette question pour le moment.',
        'fb'=>$a,
       
        'Quelles sont les traditions en Tunisie ?' => 'tu peux consulter ce site https://www.routard.com/guide/tunisie/85/traditions.htm',
    ];
    $message = $request->request->get('message');
    if ($message == 'Autre') {
        $customMessage = $request->request->get('custom_message');
        if (!empty($customMessage)) {
            $response = 'Vous avez saisi : '.$customMessage;
        } else {
            $response = 'Je suis désolé, vous devez saisir un message.';
        }
    } elseif (array_key_exists($message, $qa)) {
        $response = $qa[$message];
    } else {
        $response = 'Je suis désolé, je n\'ai pas compris votre question.';
    }


    return $this->render('moyen_transport/bot.html.twig', [
        'response' => $response
    ]);

}}