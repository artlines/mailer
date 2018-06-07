<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;

/**
 * Класс "Пользователи"
 *
 * @category   Symfony
 * @package    App\Controller
 * @author     Седов Стас, <s.sedov@nag.ru>
 * @copyright  Copyright (c) 20018 NAG LLC. (https://www.shop.nag.ru)
 * @version    0.0.4
 */
class UsersController extends Controller
{
    /**
     * @Route("/users", name="users")
     */
    public function index()
    {
      return $this->redirectToRoute('users_list', [], 301);
    }
    
    /**
     * @Route("/users/list/{page}", name="users/list", requirements={"page": "\d+"}, name="users_list")
     *
     * @param  int $page Номер страницы
     *
     * @return Response
     */
    public function list($page = 1)
    {
      $content = [
        'title_page' => 'Пользователи'
      ];

      return $this->render('dashboard/users.html.twig', $content);
    }

    /**
     * @Route("/users/{id}", name="users_show")
     *
     * @param  int $id Идентификатор записи
     *
     * @return Responce
     */
    public function show($id)
    {
      return $this->render('dashboard/users.html.twig', [
        'controller_name' => 'UsersController',
      ]);
    }

    /**
    * @Route("/users/delete/{id}", name="users_delete")
    * 
    * @param  int $id Идентификатор записи
    * 
    * @return Response
    */
    public function delete($id)
    {
      return $this->render('dashboard/users.html.twig', [
        'controller_name' => 'UsersController',
      ]);
    }

    /**
     * @Route("/users/create", name="users_create")
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function create(Request $request)
    {
      return $this->render('dashboard/users.html.twig', [
        'controller_name' => 'UsersController',
      ]);
    }
    
    /**
     * @Route("/users/edit/{id}", name="users_edit")
     * 
     * @param  Request $request
     * @param  int  $id Идентификатор записи
     * 
     * @return Responce
     */
    public function update(Request $request, $id)
    {
      return $this->render('dashboard/users.html.twig', [
        'controller_name' => 'UsersController',
      ]);
    }
  }
