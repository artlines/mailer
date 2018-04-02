<?php

namespace App\Service;

use App\Entity\Client;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class ClientManager
 * @package App\Service
 */
class ClientManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ClientManager constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $title
     * @param string $alias
     * @param array $allowIPs
     * @return Client
     */
    public function create(string $title, string $alias, ?array $allowIPs)
    {
        $client = new Client();
        $client->setTitle($title);
        $client->setAlias($alias);
        $client->setAllowIPs($allowIPs);

        try {
            $this->entityManager->persist($client);
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }

        return $client;
    }

}