<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\ActionLogger;

/**
 * Контроллер безопасности приложения.
 *
 * @category   Symfony
 * @package    App\Controller
 * @author     Седов Стас, <s.sedov@nag.ru>
 * @copyright  Copyright (c) 20018 NAG LLC. (https://www.shop.nag.ru)
 * @version    0.0.4
 */
class SecurityController extends Controller
{

    private $log = null;

    function __construct(ActionLogger $log)
    {
      $this->log = $log;
    }

    /**
     * Метод выводит форму авторизации и осуществляет аутентификацию пользователя.
     * 
     * @Route("/login", name="login")
     *
     * @param Request              $request
     * @param AuthenticationUtils  $authenticationUtils
     *
     * @return mixed
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
      $error = $authenticationUtils->getLastAuthenticationError();

      $lastUsername = $authenticationUtils->getLastUsername();

      return $this->render('security/login.html.twig', [
        'last_username' => $lastUsername,
        'error'         => $error
      ]);
    }
    
    /**
     * Метод выполняет выход пользователя из системы.
     * 
     * @Route("/logout", name="logout")
     *
     * @param Request              $request
     *
     * @return mixed
     */
    public function logout(Request $request)
    {
      # code...
    }

}
