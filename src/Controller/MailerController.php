<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Template;
use App\Service\EmailManager;
use App\Service\AuthManager;
use App\Service\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class MailerController
 * @package App\Controller
 */
class MailerController extends AbstractController
{
    /**
     * @var Template
     */
    private $template;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * MailerController constructor.
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/send")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param EmailManager $emailManager
     * @param AuthManager $auth
     *
     * @return JsonResponse
     */
    public function send(Request $request, EmailManager $emailManager, AuthManager $auth)
    {
        try {
            $this->_checkRequireParamsExists($request);

            $this->_getClient($request->request->get('client_alias'));
            $this->_getTemplate($request->request->get('template_alias'));

            $auth->checkAccessByClient($request, $this->client);

            if (!$this->template->isActive()) {
                throw new \Exception("Template with alias '{$this->template->getAlias()}' is not active.");
            }

            if (!$this->template->canUseByClient($this->client)) {
                throw new \Exception(
                    "Template with alias '{$this->template->getAlias()}' is private."
                );
            }

            $_sender = $request->request->get('sender');
            if (null === $_sender) {
                $_sender = $this->client->getSender();
            }

            $bodyData = $emailManager->generateBodyFromTemplate($this->template, $request->request->get('params'), EmailManager::CONTENT_HTML);
            $response = $emailManager->send(
                $request->request->get('subject'),
                $bodyData,
                $_sender,
                $request->request->get('send_to'),
                $request->request->get('send_cc') ?? [],
                $request->request->get('send_bcc') ?? []
            );

            $id = $this->logger->logMail($response['sm'], $request, $response['status']);

            if (!$response['status']) {
                throw new \Swift_SwiftException("E-mail was not sent. | Status: {$response['status']} | Log ID: $id");
            }

        } catch (BadRequestHttpException $e) {
            return $this->_error($request, $e->getMessage());
        } catch (\LogicException $e) {
            return $this->_error($request, $e->getMessage());
        } catch (AccessDeniedHttpException $e) {
            return $this->_error($request, $e->getMessage());
        } catch (\Swift_SwiftException $e) {
            return $this->_error($request, $e->getMessage());
        } catch (\Exception $e) {
            return $this->_error($request, $e->getMessage());
        }

        return new JsonResponse(['status' => 'ok'], 200);
    }

    /**
     * Логирует ошибку в syslog
     * Возвращает готовый JSON ответ с указанием ошибки
     *
     * @param Request $request
     * @param string $msg
     * @param int $status
     *
     * @return JsonResponse
     */
    private function _error($request, $msg, int $status = 200)
    {
        $this->logger->syslog($this->client ? $this->client->getAlias() : 'undefined')->error("$msg | request_ip: {$request->getClientIp()}");

        return new JsonResponse(
            [
                'status' => 'error',
                'errorMessage' => $msg
            ],
            $status
        );
    }

    /**
     * Check require parameters
     *
     * @param $request
     *
     * @throw BadRequestHttpException If any param doesn't exist
     */
    private function _checkRequireParamsExists($request)
    {
        $_requireParams = [
            'client_alias',
            'template_alias',
            'hash',
            'subject',
            'send_to',
            'timestamp',
            'params',
        ];

        foreach ($_requireParams as $_param)
        {
            if (null === $request->request->get($_param)) {
                throw new BadRequestHttpException("Missing '$_param' parameter.");
            }
        }
    }

    /**
     * @param string $alias
     *
     * @return Client
     *
     * @throw \LogicException If client not found by alias
     */
    private function _getClient($alias)
    {
        $this->client = $this->getDoctrine()->getRepository('App:Client')->findOneBy(['alias' => $alias]);
        if (!$this->client) {
            throw new \LogicException("Client with alias '$alias' doesn't exist.");
        }

        return $this->client;
    }

    /**
     * @param string $alias
     *
     * @return Template|null
     */
    private function _getTemplate($alias)
    {
        $this->template = $this->getDoctrine()->getRepository('App:Template')->findOneBy(['alias' => $alias]);
        if (!$this->template) {
            throw new \LogicException("Template with alias '$alias' doesn't exist.");
        }

        return $this->template;
    }

}