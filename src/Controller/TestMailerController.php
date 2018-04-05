<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Template;
use App\Service\AuthManager;
use App\Service\EmailManager;
use App\Service\Logger;
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
     * @Route("/deb")
     */
    public function deb(Request $request)
    {
        //dump();
    }


    /**
     * @Route("/send")
     *
     * @param Logger $logger
     * @param \Swift_Mailer $mailer
     * @param Request $request
     * @param AuthManager $auth
     * @param EmailManager $emailGenerator
     *
     * @return JsonResponse|Response
     */
    public function testSend(Logger $logger, \Swift_Mailer $mailer, Request $request, AuthManager $auth, EmailManager $emailGenerator)
    {
        /**
         * Проверяем, что в теле запроса есть все обязательные параметры
         */
        try {
            $this->_checkRequireParamsExists($request);
            $this->_getClient();
            $this->_validateData();
            $this->_sendEmail();
        } catch (BadRequestHttpException $e) {
            return $this->_error($e->getMessage());
        } catch (\Exception $e) {

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
        if (!$client) {
            return $this->_error("Client with alias '{$data['client']}' doesn't exist.");
        }

        /**
         * Проверяем, разрешено использовать клиент с данного IP
         */
        $isAllow = $this->_checkIp($request, $client);
        if (!$isAllow) {
            return $this->_error("IP address denied.");
        }

        /**
         * Проверяем, валиден ли хеш, что пришел в запросе
         *
         * @var boolean $isValid
         */
        $isValid = $auth->validate($data['hash'], $client, $data['timestamp']);
        if (!$isValid) {
            return $this->_error("Hash not valid.");
        }

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
         * Проверяем, активен ли шаблон
         */
        if (!$template->isActive()) {
            return $this->_error("Template is not active.");
        }

        /**
         * Проверяем, может ли клиент использовать данный шаблон
         */
        if (!$template->canUseByClient($client)) {
            return $this->_error("Template is private.");
        }

        /**
         * Определяем отправителя
         *
         * Используем указанного в запросе на отправку $data['sender']
         * Если не указан, используем указанного в клиенте $client
         */
        if (isset($data['sender'])) {
            $_sender = $data['sender'];
        } elseif (null !== $client->getSender()) {
            $_sender = $client->getSender();
        }

        /**
         * Генерируем тело письма, используя параметры из запроса
         */
        $emailBody = $emailGenerator->generate($template, $data['params']);

        /**
         * Проверка статуса отправки
         */
        $sendStatus = $this->_sendEmail($mailer, $logger, $data, $_sender, $emailBody, $request->server->get('REMOTE_ADDR'));
        if (!$sendStatus) {
            return $this->_error("Email doesn't send. Status: $sendStatus");
        }

        return new JsonResponse(['result' => 'SendStatus: ' . $sendStatus], 200);
    }

    /**
     * Возвращает готовый JSON ответ с указанием ошибки
     *
     * @param $msg
     * @return JsonResponse
     */
    private function _error($msg, int $status = 200)
    {
        return new JsonResponse(['errorMessage' => $msg], $status);
    }

    /**
     * Check require parameters
     *
     * @param Request $request
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

    private function _checkIp(Request $request, Client $client)
    {
        $allowIPs = $client->getAllowIPs();

        if (null === $allowIPs)
        {
            return true;
        }

        $ip = $request->getClientIp();
        if (in_array($ip, $allowIPs))
        {
            return true;
        }

        return false;
    }

}