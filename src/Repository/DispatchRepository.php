<?php

namespace App\Repository;

use App\Entity\Dispatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @method Dispatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dispatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dispatch[]    findAll()
 * @method Dispatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DispatchRepository extends ServiceEntityRepository
{
    const MAX_PER_PAGE = 15;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Dispatch::class);
    }

    public function getAllWithPagination($page, $filters = [])
    {
        $dispatches = [];
        $query = [];
        $qb = $this->createQueryBuilder('l')
            ->orderBy('l.datetime', 'DESC');

//        $query = $this->_checkFilters($qb, $filters);

        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(self::MAX_PER_PAGE);
        $pagerfanta->setCurrentPage($page);

        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $dispatches[] = $result;
        }

        return [
            'pagerfanta' => $pagerfanta,
            'count' => count($dispatches),
            'dispatches' => $dispatches,
            'filters' => $filters,
            'query' => $query
        ];
    }
}
