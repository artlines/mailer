<?php

namespace App\Repository;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use DateTimeImmutable;
use App\Entity\Log;

class LogRepository extends ServiceEntityRepository
{
    const MAX_PER_PAGE = 15;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Log::class);
    }

    public function getAllWithPagination($page, $filters = [])
    {
        $logs = [];
        $qb = $this->createQueryBuilder('l')
            ->orderBy('l.send_datetime', 'DESC');

        $query = $this->_checkFilters($qb, $filters);

        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(self::MAX_PER_PAGE);
        $pagerfanta->setCurrentPage($page);

        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $logs[] = $result;
        }

        return [
            'pagerfanta' => $pagerfanta,
            'count' => count($logs),
            'logs' => $logs,
            'filters' => $filters,
            'query' => $query
        ];
    }

    private function _checkFilters($qb, $filters)
    {
        $query = '';
        $dateTime = new DateTimeImmutable();
        $valueCondition = ['date_to' => '<=', 'date_from' => '>='];

        foreach ($filters as $key => $value) {
            if ($value && isset($valueCondition[$key])) {
                $operator = $valueCondition[$key];
                switch ($key) {
                    case 'date_from':
                    case 'date_to':
                        $value_array = explode('.', $value);
                        $value_date = $dateTime->setDate($value_array[2], $value_array[1], $value_array[0]);
                        $qb->andWhere("l.send_datetime {$operator} :{$key}")
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