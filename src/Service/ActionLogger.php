<?php

namespace App\Service;

use App\Entity\ActionLog;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Security;

/**
 * Класс "Логгер действий"
 *
 * @category   Symfony
 * @package    App\Entity
 * @author     Седов Стас, <s.sedov@nag.ru>
 * @copyright  Copyright (c) 20018 NAG LLC. (https://www.shop.nag.ru)
 * @version    0.0.4
 */
class ActionLogger
{
    private $em;
    private $security = null;

    function __construct(EntityManager $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function info(array $value, $diff=null)
    {
        $userId = $this->security->getUser();

        [$action, $message, $entity, $entityId] = $value;

        $log = new ActionLog;

        if (is_array($diff)){
            $log->setDiff(json_encode($diff));
        }

        $log->setAction($action);
        $log->setUserId($userId);
        $log->setDatetime('');
        $log->setMessage($message);

        $log->setMessage($message);
        $log->setEntity($entity);
        $log->setEntityId($entityId);

        $this->em->persist($log);
        $this->em->flush();

        return true;
    }

}