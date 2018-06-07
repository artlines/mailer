<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\ActionLogger;

class DashboardController extends Controller
{

  private $log = null;

  function __construct(ActionLogger $log)
  {
    $this->log = $log;
  }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
      $this->log->info([
        'login',
        'Пользователь успешно авторизовался',
        'User',
        spl_object_id(new \App\Entity\User)
      ]);

      return $this->render('dashboard/index.html.twig', [
        'controller_name' => 'DashboardController',
      ]);
    }
  }
