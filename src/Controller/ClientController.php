<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ActionLogger;

/**
 * @Route("/client")
 */
class ClientController extends Controller
{
    private $log = null;

    function __construct(ActionLogger $log)
    {
        $this->log = $log;
    }

    /**
     * @Route("/", name="client_index", methods="GET")
     * @return Response
     */
    public function index(): Response
    {
        $clientRepository = $this->getDoctrine()->getRepository(Client::class);
        return $this->render('client/index.html.twig', ['clients' => $clientRepository->findAll()]);
    }

    /**
     * @Route("/new", name="client_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();

            $id = $client->getId();

            $this->log->info([
                __METHOD__,
                'Создан новый клиент '.$id,
                'Client',
                $id
            ]);

            return $this->json([
                'result' => 'success',
                'id' => $id
            ]);

        }

        return $this->render('client/_form.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
            'action' => "/client/new",
            'title' => 'Создание клиента',
        ]);
    }


    /**
     * @Route("/{id}/edit", name="client_edit", methods="GET|POST")
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function edit(Request $request, Client $client): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        $id = $client->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->log->info([
                __METHOD__,
                'Отредактирован клиент ' . $id,
                'Client',
                $id,
            ]);

            return $this->json([
                'result' => 'success',
                'id' => $id
            ]);
        }

        return $this->render('client/_form.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
            'action' => "/client/{$id}/edit",
            'title' => 'Редактирование клиента',
        ]);
    }

    /**
     * @Route("/{id}", name="client_delete", methods="DELETE")
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function delete(Request $request, Client $client): Response
    {
        $id = $client->getId();

        if ($this->isCsrfTokenValid('delete'.$id, $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush();

            $this->log->info([
                __METHOD__,
                'Удалён клиент ' . $id,
                'Client',
                $id
            ]);
        }

        return $this->redirectToRoute('client_index');
    }

    /**
     * @Route("/{id}/update_secret", name="update_secret", methods="POST")
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    function updateClientSecret(Request $request, Client $client)
    {
        if ($request->request->has('update_secret')){
            $id = $client->getId();
            $em = $this->getDoctrine()->getManager();
            $client->setClientSecret(null);
            $em->persist($client);
            $em->flush();

            $this->log->info([
                __METHOD__,
                'Создан новый api-ключ ' . $id,
                'Client',
                $id,
            ]);

            return $this->json([
                'result' => 'success',
                'secret_key' => $client->getClientSecret()
            ]);
        }
    }
}
