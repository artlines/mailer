<?php

namespace App\Controller;

use App\Entity\SendList;
use App\Form\SendListType;
use App\Repository\SendListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ActionLogger;
use DateTimeImmutable;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;

/**
 * @Route("/send_list")
 */
class SendListController extends Controller
{
    private $log = null;
    private $dateTime;

    function __construct(ActionLogger $log)
    {
        $this->log = $log;
        $this->dateTime = new DateTimeImmutable();
    }
    /**
     * @Route("/", name="send_list_index", methods="GET")
     */
    public function index(Request $request, SendListRepository $sendListRepository): Response
    {
        $sendLists = [];
        $page = $request->query->get('page', 1);
        $qb = $this->getDoctrine()
            ->getRepository(SendList::class)
            ->findAllQueryBuilder();

        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(15);
        $pagerfanta->setCurrentPage($page);

        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $sendLists[] = $result;
        }

        $routeGenerator = function($page) {
            return '/send_list?page='.$page;
        };

        $view = new TwitterBootstrap4View();
        $options = [
            'prev_message' => '←',
            'next_message' => '→',
            'css_container_class' => 'pagination'
        ];
        $html = $view->render($pagerfanta, $routeGenerator, $options);

        return $this->render('send_list/index.html.twig', [
            'total' => $pagerfanta->getNbResults(),
            'count' => count($sendLists),
            'send_lists' => $sendLists,
            'pagination' => $html,
        ]);
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
            $sendList->setUserId($this->getUser());
            $sendList->setCreatedAt($this->dateTime);
            $em->persist($sendList);
            $em->flush();

            $this->log->info([
                'send_list_new',
                'Создан новый список рассылки '.$sendList->getId(),
                'SendList',
                $sendList->getId()
            ]);

            return $this->json([]);
        }

        return $this->render('send_list/new.html.twig', [
            'send_list' => $sendList,
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ]);
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

            $this->log->info([
                'send_list_edit',
                'Отредактирован список рассылки ' . $sendList->getId(),
                'SendList',
                $sendList->getId()
            ]);

            return $this->json([]);
        }

        return $this->render('send_list/edit.html.twig', [
            'send_list' => $sendList,
            'form' => $form->createView(),
            'user' => $this->getUser(),
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
