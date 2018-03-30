<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MailerController
 * @package App\Controller
 */
class MailerController extends AbstractController
{
    /**
     * @Route("/send")
     */
    public function send()
    {
        return new Response('ok', 200);
    }



}