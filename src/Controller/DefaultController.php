<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/send-test/{auth}")
     */
    public function testSend($auth, \Swift_Mailer $mailer)
    {
        if ($auth !== 'dtvrgg!@') die();

        if (!$this->_sendEmail($mailer))
            return new Response('neOK', 200);

        return new Response('OK', 200);
    }

    /**
     * @return Response
     *
     * @Route("/")
     */
    public function index()
    {


        return new Response('ok', 200);
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
        //$_cc = false;
        //$_bcc = false;
        $_body = "BODYBODYBODYBODY";

        /** @var \Swift_Message $sm */
        $sm = new \Swift_Message('Subject');
        $sm
            ->setFrom($_from)
            ->setTo($_to)
            //->setCc($_cc)
            //->setBcc($_bcc)
            ->setBody($_body);

        $status = $mailer->send($sm);

        return true;
    }
}