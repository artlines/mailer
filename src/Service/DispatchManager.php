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

    /**
     * @param $alias
     * @return null|object
     */
    private function _getStatusIdByAlias($alias)
    {
        return $this->entityManager
            ->getRepository(DispatchStatus::class)
            ->findOneBy(['alias' => $alias])
        ;
    }

    public function setDispatchStatus($alias, Dispatch $dispatch)
    {
        $status =  $this->_getStatusIdByAlias($alias);
        $dispatch->setStatus($status);

        $this->entityManager->persist($dispatch);
        $this->entityManager->flush();

        return $status;

    }

    public function getDispatches($status)
    {
        $dispatches = $this->entityManager->getRepository(Dispatch::class);
        $dispatchStatus = $this->_getStatusIdByAlias($status);

        return $dispatches->createQueryBuilder('d')
            ->where('d.date_send <= :date_send')
            ->setParameter('date_send', new \DateTimeImmutable('now'))
            ->andWhere('d.status = :status')
            ->setParameter('status', $dispatchStatus)
            ->getQuery()
            ->getResult();
    }

    public function getDispatchById($id)
    {
        $dispatch = $this->entityManager->getRepository(Dispatch::class);
        return $dispatch->findOneBy(['id' => $id]);
    }





}
