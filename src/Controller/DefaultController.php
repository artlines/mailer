<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
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
     * @Route("/")
     */
    public function index(LoggerInterface $logger)
    {
        $rand = rand(1,1000);

        $logger->alert('Alert: ' . $rand);
        $logger->critical('Critical: ' . $rand);
        $logger->debug('Debug: ' . $rand);
        $logger->emergency('Emergency: ' . $rand);
        $logger->error('Error: ' . $rand);
        $logger->info('Info: ' . $rand);
        $logger->notice('Notice: ' . $rand);
        $logger->warning('Warning: ' . $rand);

        return new Response('ok', 200);
    }

}