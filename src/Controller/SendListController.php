<?php

namespace App\Controller;

use App\Entity\SendList;
use App\Form\SendListType;
use App\Repository\SendListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/send/list")
 */
class SendListController extends Controller
{
    /**
     * @Route("/", name="send_list_index", methods="GET")
     */
    public function index(SendListRepository $sendListRepository): Response
    {
        return $this->render('send_list/index.html.twig', ['send_lists' => $sendListRepository->findAll()]);
    }

    /**
     * @Route("/new", name="send_list_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $sendList = new SendList();
        $form = $this->createForm(SendListType::class, $sendList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sendList);
            $em->flush();

            return $this->redirectToRoute('send_list_index');
        }

        return $this->render('send_list/new.html.twig', [
            'send_list' => $sendList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="send_list_show", methods="GET")
     */
    public function show(SendList $sendList): Response
    {
        return $this->render('send_list/show.html.twig', ['send_list' => $sendList]);
    }

    /**
     * @Route("/{id}/edit", name="send_list_edit", methods="GET|POST")
     */
    public function edit(Request $request, SendList $sendList): Response
    {
        $form = $this->createForm(SendListType::class, $sendList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('send_list_edit', ['id' => $sendList->getId()]);
        }

        return $this->render('send_list/edit.html.twig', [
            'send_list' => $sendList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="send_list_delete", methods="DELETE")
     */
    public function delete(Request $request, SendList $sendList): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sendList->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sendList);
            $em->flush();
        }

        return $this->redirectToRoute('send_list_index');
    }
}
