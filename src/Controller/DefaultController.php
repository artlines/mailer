<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController
{
    /**
     * @return Response
     *
     * @Route("/")
     */
    public function index()
    {
        return new Response('hi', 200);
    }
}