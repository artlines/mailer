<?php

namespace App\Repository;

use App\Entity\DispatchStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DispatchStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method DispatchStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method DispatchStatus[]    findAll()
 * @method DispatchStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DispatchStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DispatchStatus::class);
    }

//    /**
//     * @return DispatchStatus[] Returns an array of DispatchStatus objects
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
    public function findOneBySomeField($value): ?DispatchStatus
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
