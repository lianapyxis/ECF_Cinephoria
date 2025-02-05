<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerController extends AbstractController
{
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer, $firstname, $lastname, $email, $subject, $message): string
    {
        $email = (new Email())
            ->from(Address::create($firstname.' '.$lastname.' <'.$email.'>'))
            ->to('lianapyxis1@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text($message);
/*            ->html('<p>See Twig integration for better HTML integration!</p>')*/

        try {
            $mailer->send($email);
            echo "L'envoi est réussi";
            return true;

        } catch (TransportExceptionInterface $e){

            return $e->getMessage();
        }

    }
}