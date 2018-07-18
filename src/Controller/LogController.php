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
 * @Route("/action_log")
 */
class LogController extends Controller
{

    /**
     * @Route("/", name="action_log_index", methods="GET|POST")
     */
    public function index(Request $request, LogRepository $LogRepository): Response
    {
        $pagination = new TwitterBootstrap4View();
        $page = $request->query->get('page', 1);
        $filters = $request->query->all();
        $result = $LogRepository->getAllWithPagination($page, $filters);
        $result['entities'] = $LogRepository->getEntities();
        $query = $result['query'];
        $paginationOptions = [
            'prev_message' => '←',
            'next_message' => '→',
            'css_container_class' => 'pagination'
        ];
        $routeGenerator = function ($page) use ($query) {
            return '/action_log?page=' . $page . $query;
        };


        $result['total'] = $result['pagerfanta']->getNbResults();
        $result['pagination'] = $pagination->render($result['pagerfanta'], $routeGenerator, $paginationOptions);

        if ($request->isXmlHttpRequest()) {
            return $this->render('action_log/_list.html.twig', $result);
        }

        return $this->render('action_log/index.html.twig', $result);
    }

}
