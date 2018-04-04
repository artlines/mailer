<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
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