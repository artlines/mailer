<?php

namespace App\Controller;

use App\Entity\Dispatch;
use App\Form\DispatchType;
use App\Repository\DispatchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ActionLogger;
use DateTimeImmutable;

/**
 * @Route("/dispatch")
 */
class DispatchController extends Controller
{
    private $log = null;
    private $dateTime;

    function __construct(ActionLogger $log)
    {
        $this->log = $log;
        $this->dateTime = new DateTimeImmutable();
    }

    /**
     * @Route("/", name="dispatch_index", methods="GET")
     */
    public function index(DispatchRepository $dispatchRepository): Response
    {
        return $this->render('dispatch/index.html.twig', ['dispatches' => $dispatchRepository->findAll()]);
    }

    /**
     * @Route("/new", name="dispatch_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $dispatch = new Dispatch();
        $form = $this->createForm(DispatchType::class, $dispatch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dateTime = new DateTimeImmutable();
            $dispatch->setDatetime($this->dateTime);
            $em = $this->getDoctrine()->getManager();
            $em->persist($dispatch);
            $em->flush();

            return $this->redirectToRoute('dispatch_index');
        }

        return $this->render('dispatch/new.html.twig', [
            'dispatch' => $dispatch,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dispatch_show", methods="GET")
     */
    public function show(Dispatch $dispatch): Response
    {
        return $this->render('dispatch/show.html.twig', ['dispatch' => $dispatch]);
    }

    /**
     * @Route("/{id}/edit", name="dispatch_edit", methods="GET|POST")
     */
    public function edit(Request $request, Dispatch $dispatch): Response
    {
        $form = $this->createForm(DispatchType::class, $dispatch);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dispatch_edit', ['id' => $dispatch->getId()]);
        }

        return $this->render('dispatch/edit.html.twig', [
            'dispatch' => $dispatch,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dispatch_delete", methods="DELETE")
     */
    public function delete(Request $request, Dispatch $dispatch): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dispatch->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($dispatch);
            $em->flush();
        }

        return $this->redirectToRoute('dispatch_index');
    }
}
