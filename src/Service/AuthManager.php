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
     * @param array $data Request parameters
     */
    public function checkAccessByClient(Request $request, Client $client, $data)
    {
        $this->request = $request;
        $this->client = $client;

        $this->_validateHash($data['hash'], $data['timestamp']);
        $this->_checkPermissionByIp();
    }

    private function _validateHash($hash, $timestamp)
    {
        $freshHash = hash('sha256', $this->client->getClientSecret() . $timestamp . $this->client->getAlias());

        if ($hash === $freshHash)
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