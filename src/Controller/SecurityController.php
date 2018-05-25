<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends Controller
{
    
    /**
     * @Route("/login", name="login")
     *
     * @param Request              $request
     * @param AuthenticationUtils  $authenticationUtils
     *
     * @return mixed
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
    	$error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error
            ]);

    }

}