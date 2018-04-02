<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Template;
use App\Service\AuthInterface;
use App\Service\EmailGenerator;
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
     * @Route("/send")
     */
    public function testSend(\Swift_Mailer $mailer, Request $request, AuthInterface $auth, EmailGenerator $emailGenerator)
    {
        /**
         * Проверяем, что в теле запроса есть все обязательные параметры
         */
        try {
            $this->_checkRequireParamsExists($request);
        } catch (BadRequestHttpException $e) {
            return $this->_error($e->getMessage());
        }

        /**
         * Для удобства определяем массив с пришедшими POST-параметрами
         */
        $data = $request->request->all();

        /**
         * Ищем клиента по его алиасу
         *
         * @var Client $client
         */
        $client = $this->getDoctrine()
            ->getRepository('App:Client')->findOneBy(['alias' => $data['client']]);
        if (!$client)
            return $this->_error("Client with alias '{$data['client']}' doesn't exist.");

        /**
         * Проверяем, валиден ли хеш, что пришел в запросе
         *
         * @var boolean $isValid
         */
        $isValid = $auth->validate($data['hash'], $client, $data['timestamp']);
        if (!$isValid)
            return $this->_error("Hash not valid.");

        /**
         * Ищем шаблон по его алиасу
         *
         * @var Template $template
         */
        $template = $this->getDoctrine()
            ->getRepository("App:Template")->findOneBy(['alias' => $data['template']]);
        if (!$template)
            return $this->_error("Template with alias '{$data['template']}' doesn't exist.");

        /**
         * Генерируем тело письма, используя параметры из запроса
         */
        $param_array = json_decode($data['params'], true);
        $emailBody = $emailGenerator->generate($template, $param_array);

        /**
         * Проверка статуса отправки
         */
        $sendStatus = $this->_sendEmail($emailBody, $mailer);
        if (!$sendStatus)
            return $this->_error("Email doesn't send. Send status: $sendStatus");

        return new Response('ok', 201);
    }

    /**
     * Возвращает готовый JSON ответ с указанием ошибки
     *
     * @param $msg
     * @return JsonResponse
     */
    private function _error($msg)
    {
        return new JsonResponse(['errorMessage' => $msg]);
    }

    /**
     * Собирает и (не)отправляет email
     *
     * @param \Swift_Mailer $mailer
     * @return bool
     */
    private function _sendEmail($emailBody, \Swift_Mailer $mailer)
    {
        $_from = 'no-reply@mailer.soa.dev.nag.ru';
        $_to = 'e.nachuychenko@nag.ru';

        /** @var \Swift_Message $sm */
        $sm = new \Swift_Message('Subject');
        $sm
            ->setFrom($_from)
            ->setTo($_to)
            ->setBody($emailBody, 'text/html');

        return $mailer->send($sm);
    }

    /**
     * Check require parameters
     */
    private function _checkRequireParamsExists(Request $request)
    {
        $_requireParams = [
            'client',
            'hash',
            'subject',
            'send_to',
            'template',
            'timestamp',
            'params',
        ];

        foreach ($_requireParams as $_param)
        {
            if (null === $request->request->get($_param))
                throw new BadRequestHttpException("Missing '$_param' parameter.");
        }
    }

}