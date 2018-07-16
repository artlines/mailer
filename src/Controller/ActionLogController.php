<?php

namespace App\Controller;

use App\Entity\ActionLog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/action_log")
 */
class ActionLogController extends Controller
{

    /**
     * @Route("/", name="action_log_index", methods="GET|POST")
     */
    public function index(): Response
    {
        //селект с разделами, фильтр по дате, пагинация при выборе подгружаются логи по разделу
        $actionLogs = $this->getDoctrine()->getRepository(ActionLog::class)->findBy(['isActive' => true], ['id' => 'ASC']);
        return $this->render('user/index.html.twig', ['users' => $actionLogs]);
    }

}
