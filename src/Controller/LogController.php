<?php

namespace App\Controller;

use App\Entity\Log;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LogRepository;
use Pagerfanta\View\TwitterBootstrap4View;

/**
 * @Route("/log")
 */
class LogController extends Controller
{

    /**
     * @Route("/", name="log_index", methods="GET|POST")
     * @param Request $request
     * @param LogRepository $LogRepository
     * @return Response
     */
    public function index(Request $request, LogRepository $LogRepository): Response
    {
        $pagination = new TwitterBootstrap4View();
        $page = $request->query->get('page', 1);
        $filters = $request->query->all();
        $result = $LogRepository->getAllWithPagination($page, $filters);
        $query = $result['query'];
        $paginationOptions = [
            'prev_message' => '←',
            'next_message' => '→',
            'css_container_class' => 'pagination'
        ];
        $routeGenerator = function ($page) use ($query) {
            return '/log?page=' . $page . $query;
        };


        $result['total'] = $result['pagerfanta']->getNbResults();
        $result['pagination'] = $pagination->render($result['pagerfanta'], $routeGenerator, $paginationOptions);

        if ($request->isXmlHttpRequest()) {
            return $this->render('log/_list.html.twig', $result);
        }

        return $this->render('log/index.html.twig', $result);
    }

    /**
     * @Route("/{id}", name="log_show", methods="GET|POST")
     */
    public function show(Request $request, Log $log): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('log/show_template.html.twig', ['log' => $log->getMailBody()]);
        }

        return $this->render('log/show.html.twig', ['log' => $log, 'title' => $log->getMailSubject()]);
    }

}
