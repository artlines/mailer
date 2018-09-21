<?php

namespace App\Controller;

use App\Entity\Template;
use App\Entity\Client;
use App\Form\TemplateType;
use App\Repository\TemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ActionLogger;

/**
 * @Route("/template")
 */
class TemplateController extends Controller
{
    const CLIENT = 'mailer';
    private $log = null;

    function __construct(ActionLogger $log)
    {
        $this->log = $log;
    }

    /**
     * @Route("/", name="template_index", methods="GET")
     */
    public function index(TemplateRepository $templateRepository): Response
    {
        return $this->render('template/index.html.twig', ['templates' => $templateRepository->findAll()]);
    }

    /**
     * @Route("/new", name="template_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $template = new Template();
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $client = $em->getRepository(Client::class)->findOneBy(['alias' => self::CLIENT]);
            $template->addClient($client);
            $em->persist($template);
            $em->flush();
            $id = $template->getId();

            $this->log->info([
                __METHOD__,
                'Создан новый шаблон ' . $id,
                'Template',
                $id
            ]);

            return $this->json([
                'result' => 'success',
                'id' => $id
            ]);
        }

        return $this->render('template/_form.html.twig', [
            'template' => $template,
            'form' => $form->createView(),
            'action' => '/template/new',
            'title' => 'Создание шаблона',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="template_edit", methods="GET|POST")
     */
    public function edit(Request $request, Template $template): Response
    {
        $id = $template->getId();
        $old_text = $template->getText();
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->log->info([
                __METHOD__,
                'Отредактирован шаблон ' . $id,
                'Template',
                $id
            ], [
                'field' => 'text',
                'old_value' => $old_text
            ]);

            return $this->json([
                'result' => 'success',
                'id' => $id
            ]);
        }

        return $this->render('template/_form.html.twig', [
            'template' => $template,
            'form' => $form->createView(),
            'action' => "/template/{$id}/edit",
            'title' => 'Редактирование шаблона',
        ]);
    }

    /**
     * @Route("/{id}", name="template_delete", methods="DELETE")
     */
    public function delete(Request $request, Template $template): Response
    {
        $id = $template->getId();
        if ($this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($template);
            $em->flush();

            $this->log->info([
                __METHOD__,
                'Удалён шаблон ' . $id,
                'Template',
                $id
            ]);
        }

        return $this->redirectToRoute('template_index');
    }
}