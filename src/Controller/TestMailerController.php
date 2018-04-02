<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
     * @Route("/debug")
     */
    public function debug()
    {
        $clientRepo = $this->getDoctrine()->getRepository('App:Client');
        $client1 = $clientRepo->find(1);
        $client2 = $clientRepo->find(1);

        dump($client1);
        dump($client2);

        return new Response('ok', 200);
    }

    /**
     * @Route("/send/{auth}")
     */
    public function testSend($auth, \Swift_Mailer $mailer, Request $request)
    {
        if ($auth !== 'dtvrgg!@') die();

        try {
            $this->_checkRequest($request);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(['errorMessage' => $e->getMessage()]);
        }

        $sendStatus = $this->_sendEmail($mailer);

        if (!$sendStatus)
            return new Response('neOK', 200);

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

    /**
     * Check params
     */
    private function _checkRequest(Request $request)
    {
        $_requireParams = [
            'hash',
            'client',
            'template',
            'subject',
            'params',
            'send_to'
        ];

        foreach ($_requireParams as $_param)
        {
            if (null === $request->request->get($_param))
            {
                throw new BadRequestHttpException("Missing '$_param' parameter.");
                break;
            }
        }
    }

}