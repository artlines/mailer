<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;

class EmailGenerator
{
    /**
     * EmailGenerator constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


}