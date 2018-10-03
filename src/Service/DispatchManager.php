<?php

namespace App\Service;

use App\Entity\Dispatch;
use App\Entity\DispatchStatus;
use App\Entity\DispatchLog;
use Doctrine\Common\Collections\ArrayCollection;
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
    private function _getStatusByAlias($alias)
    {
        $dispatchStatuses = $this->entityManager->getRepository(DispatchStatus::class);

        return $dispatchStatuses
            ->createQueryBuilder('ds')
            ->where('ds.alias IN (:status)')
            ->setParameter('status', $alias)
            ->getQuery()
            ->getResult();
        ;
    }

    public function setDispatchStatus($alias, Dispatch $dispatch)
    {
        $status =  $this->_getStatusByAlias($alias)[0];
        $dispatch->setStatus($status);

        $this->entityManager->persist($dispatch);
        $this->entityManager->flush();

        return $status;

    }

    public function getDispatches($status)
    {
        $dispatches = $this->entityManager->getRepository(Dispatch::class);
        $dispatchStatus = $this->_getStatusByAlias($status);

        return $dispatches->createQueryBuilder('d')
            ->where('d.date_send <= :date_send')
            ->setParameter('date_send', new \DateTimeImmutable('now'))
            ->andWhere('d.status IN (:status)')
            ->setParameter('status', $dispatchStatus)
            ->getQuery()
            ->getResult();
    }

    public function getDispatchById($id)
    {
        return $this->entityManager
            ->getRepository(Dispatch::class)
            ->findOneBy(['id' => $id]);
    }


    public function setDispatchLog(Dispatch $dispatch, $emails)
    {
        foreach ($emails as $email){
            $dispatchLog = new DispatchLog();
            $dispatchLog->setDispatch($dispatch);
            $dispatchLog->setEmail($email);
            $dispatchLog->setStatus(false);

            $this->entityManager->persist($dispatchLog);
            $this->entityManager->flush();
        }

        return true;
    }

    public function cleanDispatchLog(Dispatch $dispatch, $email)
    {
        $dispatchLog = $this->entityManager->getRepository(DispatchLog::class);

        return $dispatchLog->createQueryBuilder('dl')
            ->delete()
            ->where('dl.dispatch = :dispatch')
            ->setParameter('dispatch', $dispatch)
            ->andWhere('dl.email = (:email)')
            ->setParameter('email', $email)
            ->getQuery()
            ->execute();
    }

    public function getUndeliveredEmails(Dispatch $dispatch): Array
    {
        $dispatchLog = $this->entityManager->getRepository(DispatchLog::class);

        $result = $dispatchLog->createQueryBuilder('dl')
            ->select('dl.email')
            ->where('dl.dispatch = :dispatch')
            ->setParameter('dispatch', $dispatch)
            ->andWhere('dl.status = (:status)')
            ->setParameter('status', false)
            ->getQuery()
            ->getResult();

        return array_map(function($el){
            return trim($el['email']);
        }, $result);
    }

}
