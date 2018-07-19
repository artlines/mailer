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
use Pagerfanta\View\TwitterBootstrap4View;

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
    public function index(Request $request, DispatchRepository $dispatchRepository): Response
    {
        $pagination = new TwitterBootstrap4View();
        $page = $request->query->get('page', 1);
        $filters = $request->query->all();
        $result = $dispatchRepository->getAllWithPagination($page, $filters);
        $query = $result['query'];
        $paginationOptions = [
            'prev_message' => '←',
            'next_message' => '→',
            'css_container_class' => 'pagination'
        ];
        $routeGenerator = function ($page) use ($query) {
            return '/dispatch?page=' . $page . $query;
        };


        $result['total'] = $result['pagerfanta']->getNbResults();
        $result['pagination'] = $pagination->render($result['pagerfanta'], $routeGenerator, $paginationOptions);

        if ($request->isXmlHttpRequest()) {
            return $this->render('dispatch/_list.html.twig', $result);
        }

        return $this->render('dispatch/index.html.twig', $result);
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
            $id = $dispatch->getId();

            return $this->json([
                'result' => 'success',
                'id' => $id
            ]);
        }

        return $this->render('dispatch/_form.html.twig', [
            'dispatch' => $dispatch,
            'form' => $form->createView(),
            'user' => $this->getUser(),
            'action' => '/dispatch/new',
            'title' => 'Создание списка рассылки',
        ]);


    }

    /**
     * @Route("/{id}", name="dispatch_show", methods="GET|POST")
     */
    public function show(Request $request, Dispatch $dispatch): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('dispatch/show_template.html.twig', ['dispatch' => $dispatch->getMailBody()]);
        }

        return $this->render('dispatch/show.html.twig', ['dispatch' => $dispatch, 'title' => $dispatch->getSubject()]);
    }

}
