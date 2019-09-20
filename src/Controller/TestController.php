<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Messenger\MessageHandler;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index($mailerDsn)
    {
        $transport = Transport::fromDsn($mailerDsn);
        $handler = new MessageHandler($transport);

        $bus = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator([
                SendEmailMessage::class => [$handler],
            ])),
        ]);

        $email = (new Email())
            ->from('evila@stcable.rs')
            ->to('tibor.rac@gmail.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>test</p>');


        $mailer = new Mailer($transport, $bus);
        $mailer->send($email);

        return new Response('<p>test</p>');
    }
}
