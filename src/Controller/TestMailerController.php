<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function testSend($auth, \Swift_Mailer $mailer)
    {
        if ($auth !== 'dtvrgg!@') die();

        $sendStatus = $this->_sendEmail($mailer);

        if (!$sendStatus)
            return new Response('neOK', 200);

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
        $_body = "BODYBODYBODYBODY";

        /** @var \Swift_Message $sm */
        $sm = new \Swift_Message('Subject');
        $sm
            ->setFrom($_from)
            ->setTo($_to)
            //->setCc($_cc)
            //->setBcc($_bcc)
            ->setBody($_body);

        return $mailer->send($sm);
    }
}