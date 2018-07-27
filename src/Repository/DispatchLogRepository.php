<?php

namespace App\Repository;

use App\Entity\DispatchLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DispatchLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method DispatchLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method DispatchLog[]    findAll()
 * @method DispatchLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DispatchLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DispatchLog::class);
    }

//    /**
//     * @return DispatchLog[] Returns an array of DispatchLog objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DispatchLog
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
