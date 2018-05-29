<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Logger;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @param Logger $logger
     *
     * @return Response
     */
    public function index(Logger $logger)
    {
        //$logger->syslog()->info("asd");

        return new Response('ok', 200);
    }

}