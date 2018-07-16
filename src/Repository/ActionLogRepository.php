<?php

namespace App\Repository;

use App\Entity\ActionLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;
use Doctrine\Common\Collections\Criteria;

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


    public function getAllWithPagination($page, $filters = null)
    {

        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.datetime', 'ASC');

        if ($filters) {
            foreach ($filters as $key => $filter) {
                $qb->andWhere("a.{$key} {$filter['sign']} :{$key}")
                    ->setParameter($key, $filter['value']);
            }
        }

        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(self::MAX_PER_PAGE);
        $pagerfanta->setCurrentPage($page);

        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $actionLogs[] = $result;
        }

        $routeGenerator = function ($page) {
            return '/action_log?page=' . $page;
        };

        $view = new TwitterBootstrap4View();
        $options = [
            'prev_message' => '←',
            'next_message' => '→',
            'css_container_class' => 'pagination'
        ];
        $html = $view->render($pagerfanta, $routeGenerator, $options);

        return [
            'total' => $pagerfanta->getNbResults(),
            'count' => count($actionLogs),
            'logs' => $actionLogs,
            'pagination' => $html
        ];
    }
}
