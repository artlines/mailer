<?php

namespace App\Service;

use App\Entity\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthManager
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Request $request
     * @param Client $client
     */
    public function checkAccessByClient(Request $request, Client $client)
    {
        $this->request = $request;
        $this->client = $client;

        $this->_validateHash();
        $this->_checkPermissionByIp();
    }

    private function _validateHash()
    {
        $freshHash = hash('sha256', $this->client->getClientSecret() . $this->request->request->get('timestamp') . $this->client->getAlias());

        if ($this->request->request->get('hash') === $freshHash)
            return true;

        throw new AccessDeniedHttpException("Hash not valid.");
    }

    private function _checkPermissionByIp()
    {
        $allowIPs = $this->client->getAllowIPs();
        $ip = $this->request->getClientIp();

        if (null === $allowIPs) {
            return true;
        }

        if (in_array($ip, $allowIPs)) {
            return true;
        }

        throw new AccessDeniedHttpException("Access denied by client IP.");
    }
}