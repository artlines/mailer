<?php

namespace App\Repository;

use App\Entity\SendList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;

/**
 * @method SendList|null find($id, $lockMode = null, $lockVersion = null)
 * @method SendList|null findOneBy(array $criteria, array $orderBy = null)
 * @method SendList[]    findAll()
 * @method SendList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SendListRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SendList::class);
    }


    public function findAllQueryBuilder()
    {
        return $this->createQueryBuilder('sendList')->addOrderBy('sendList.id', 'ASC');
    }



    public function getAllWithPagination($page)
    {
        $qb = $this->findAllQueryBuilder();

        $adapter = new DoctrineORMAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(15);
        $pagerfanta->setCurrentPage($page);

        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $sendLists[] = $result;
        }

        $routeGenerator = function($page) {
            return '/send_list?page='.$page;
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
            'count' => count($sendLists),
            'send_lists' => $sendLists,
            'pagination' => $html
        ];
    }

}
