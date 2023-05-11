<?php
namespace App\services;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
class Mailer
{
    /**
     * @var MailerInterface
    */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer=$mailer;
    }
    public function sendEmail(string $receiver, string $code)
    {
        $email = (new Email())
        ->from(Address::create('Swift Transit <TunisPublicTransport2023@hotmail.com>'))

        ->to($receiver)
        ->subject('Récupération de mot de passe')
        ->text('Votre code de récupération de mot de passe est :' .$code);
        // path to your Twig template
        $this->mailer->send($email);

    }
}
