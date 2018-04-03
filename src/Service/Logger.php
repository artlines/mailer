<?php

namespace App\Service;

use App\Entity\Log;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class Logger
{
    /** @var EntityManager $entityManager */
    private $entityManager;

    /**
     * Logger constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Swift_Message $sm
     *
     * @return bool
     */
    public function logMail(\Swift_Message $sm)
    {
        $log = new Log();

        $log->setEmailFrom($sm->getSender());
        $log->setEmailTo($sm->getTo());
        $log->setEmailCc($sm->getCc());
        $log->setEmailBcc($sm->getBcc());

        $log->setMailSubject($sm->getSubject());
        $log->setMailBody($sm->getBody());

        $log->setIpAddress('');

        try {
            $this->entityManager->persist($log);
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
            return false;
        } catch (ORMException $e) {
            return false;
        }

        return true;
    }

}