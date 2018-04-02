<?php

namespace App\Service;

class AuthInterface
{
    public function validate($hash, $key, $timestamp, $clientAlias)
    {
        $freshHash = hash('sha256', $key . $timestamp . $clientAlias);

        if ($hash === $freshHash)
            return true;

        return false;
    }
}