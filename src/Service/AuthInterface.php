<?php

namespace App\Service;

use App\Entity\Client;

class AuthInterface
{
    public function validate($hash, Client $client, $timestamp)
    {
        $freshHash = hash('sha256', $client->getClientSecret() . $timestamp . $client->getAlias());

        if ($hash === $freshHash)
            return true;

        return false;
    }
}