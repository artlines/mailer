<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TestMailerController
 * @package App\Controller
 *
 * @Route("/test")
 */
class TestMailerController extends AbstractController
{
    /**
     * @Route("/send/{auth}")
     */
    public function testSend($auth, \Swift_Mailer $mailer, Request $request)
    {
        if ($auth !== 'dtvrgg!@') die();

        $sendStatus = $this->_sendEmail($mailer);

        if (!$sendStatus)
            return new Response('neOK', 200);

        var_dump($request);

        return new Response('ok', 201);
    }


    /**
     * Send email
     *
     * @param \Swift_Mailer $mailer
     * @return bool
     */
    private function _sendEmail(\Swift_Mailer $mailer)
    {
        $_from = 'no-reply@mailer.soa.dev.nag.ru';
        $_to = 'e.nachuychenko@nag.ru';
        $_body = "BODYBODYBODYBODY";

        /** @var \Swift_Message $sm */
        $sm = new \Swift_Message('Subject');
        $sm
            ->setFrom($_from)
            ->setTo($_to)
            ->setBody($_body);

        return $mailer->send($sm);
    }
}