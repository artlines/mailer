<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Method({"GET"})
     */
    public function index()
    {
        return $this->redirectToRoute('users_list', [], 301);
    }

    /**
     * @Route("/users/list/{page}", name="users_list", requirements={"page": "\d+"})
     * @Method({"GET"})
     *
     * @param  int $page Номер страницы
     *
     * @return Response
     */
    public function list($page = 1)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findBy(['isActive' => true],['id' => 'ASC']);

        $content = [
            'title_page' => 'Пользователи',
            'template' => 'users',
            'data' => $users
        ];

//        dump($users);

        return $this->render('dashboard/index.html.twig', $content);
    }

    /**
     * @Route("/users/{id}", name="users_show")
     * @Method({"GET"})
     *
     * @param  int $id Идентификатор записи
     *
     * @return Responce
     */
    public function show($id)
    {
        return $this->render('dashboard/list.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * @Route("/users/{id}", name="users_delete")
     * @Method({"DELETE"})
     *
     * @param  int $id Идентификатор записи
     *
     * @return Response
     */
    public function delete($id)
    {
        return $this->render('dashboard/list.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * @Route("/users/create", name="users_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request)
    {
        //create
        return $this->redirectToRoute('users_list', []);
    }

    /**
     * @Route("/users/{id}", name="users_edit")
     * @Method({"PUT"})
     *
     * @param  Request $request
     * @param  int $id Идентификатор записи
     *
     * @return Responce
     */
    public function update(Request $request, $id)
    {
        return $this->render('dashboard/list.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
}
