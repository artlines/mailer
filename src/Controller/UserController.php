<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\ActionLogger;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    private $log = null;

    function __construct(ActionLogger $log)
    {
        $this->log = $log;
    }

    /**
     * @Route("/", name="user_index", methods="GET")
     */
    public function index(): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findBy(['isActive' => true], ['id' => 'ASC']);
        return $this->render('user/index.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/new", name="user_new", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $encoded = $encoder->encodePassword($user, $data->getPassword());
            $user->setPassword($encoded);
            $api_key = $data->getApi() ? md5(random_bytes(18)) : null;
            $user->setApiKey($api_key);
            $em->persist($user);
            $em->flush();

            $this->log->info([
                'user_new',
                'Создан новый пользователь',
                'User',
                $user->getId()
            ]);

            return $this->json([]);
        }
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user)->remove('password');;
        $form->handleRequest($request);
        $data = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getApiKey()) {
                $api_key = $data->getApi() ? md5(random_bytes(18)) : null;
                $user->setApiKey($api_key);
            }
            $this->getDoctrine()->getManager()->flush();

            $this->log->info([
                'user_edit',
                'Отредактирован пользователь' . $user->getId(),
                'User',
                $user->getId()
            ]);

            return $this->json([]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $user->setIsActive(0);
            $em->persist($user);
            $em->flush();

            $this->log->info([
                'user_delete',
                'Удалён пользователь' . $user->getId(),
                'User',
                $user->getId()
            ]);
        }

        return $this->redirectToRoute('user_index');
    }
}
