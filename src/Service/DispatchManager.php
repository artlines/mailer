<?php

namespace App\Service;

use App\Entity\Dispatch;
use App\Entity\DispatchStatus;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class DispatchManager
 * @package App\Service
 */
class DispatchManager
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

    public function getDispatches($status)
    {
        $dispatches = $this->entityManager->getRepository(Dispatch::class);
        $dispatchStatus = $this->entityManager
            ->getRepository(DispatchStatus::class)
            ->findOneBy(['alias' => $status])
        ;

        return $dispatches->findBy(['status_id' => $dispatchStatus->getId()],['date_send' => 'ASC']);
    }



}
