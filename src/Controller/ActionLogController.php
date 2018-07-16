<?php

namespace App\Controller;

use App\Entity\ActionLog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ActionLogRepository;

/**
 * @Route("/action_log")
 */
class ActionLogController extends Controller
{

    /**
     * @Route("/", name="action_log_index", methods="GET|POST")
     */
    public function index(Request $request, ActionLogRepository $actionLogRepository): Response
    {
        //селект с разделами, фильтр по дате, пагинация при выборе подгружаются логи по разделу
        $page = $request->query->get('page', 1);
        $result = $actionLogRepository->getAllWithPagination($page);

        return $this->render('action_log/index.html.twig', $result);
    }

}
