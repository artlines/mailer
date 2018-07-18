<?php

namespace App\Repository;

use App\Entity\ActionLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use DateTimeImmutable;

/**
 * @method ActionLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionLog[]    findAll()
 * @method ActionLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionLogRepository extends ServiceEntityRepository
{
    const MAX_PER_PAGE = 15;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ActionLog::class);
    }


    public function getAllWithPagination($page, $filters = [])
    {
        $actionLogs = [];
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.datetime', 'DESC');

        $query = $this->_checkFilters($qb, $filters);

        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(self::MAX_PER_PAGE);
        $pagerfanta->setCurrentPage($page);

        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $actionLogs[] = $result;
        }

        return [
            'pagerfanta' => $pagerfanta,
            'count' => count($actionLogs),
            'logs' => $actionLogs,
            'filters' => $filters,
            'query' => $query
        ];
    }

    public function getEntities()
    {
        return $this->createQueryBuilder('a')
            ->select('a.entity')
            ->distinct('a.entity')
            ->getQuery()
            ->getResult();
    }

    private function _checkFilters($qb, $filters)
    {
        $query = '';
        $dateTime = new DateTimeImmutable();
        $valueCondition = ['entity' => '=', 'date_to' => '<=', 'date_from' => '>='];

        foreach ($filters as $key => $value) {
            if ($value && isset($valueCondition[$key])) {
                $operator = $valueCondition[$key];
                switch ($key) {
                    case 'entity':
                        $qb->andWhere("a.{$key} $operator :{$key}")
                            ->setParameter($key, $value);
                        $query .= "&{$key}=" . $value;
                        break;
                    case 'date_from':
                    case 'date_to':
                        $value_array = explode('-', $value);
                        $value_date = $dateTime->setDate($value_array[2], $value_array[1], $value_array[0]);
                        $qb->andWhere("a.datetime {$operator} :{$key}")
                            ->setParameter($key, $value_date);
                        $query .= "&{$key}=" . $value;
                        break;
                    default:
                        break;
                }
            }
        }

        return $query;
    }

}
