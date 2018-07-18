<?php

namespace App\Service;

use App\Entity\Log;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger as Monolog_Logger;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @param boolean $isSend
     *
     * @return bool
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function logMail(\Swift_Message $sm, $request, $isSend)
    {
        $ipAddress = $request->getClientIp();

        $log = new Log();

        $_from = key($sm->getFrom());

        $log->setEmailFrom($_from);
        $log->setEmailTo($sm->getTo());
        $log->setEmailCc($sm->getCc());
        $log->setEmailBcc($sm->getBcc());

        $log->setMailSubject($sm->getSubject());
        $log->setMailBody($sm->getBody());

        $log->setIpAddress($ipAddress);
        $log->setSendstatus($isSend);

        $this->entityManager->persist($log);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param string $client
     *
     * @return Monolog_Logger
     */
    public function syslog(string $client = 'null'): Monolog_Logger
    {
        $log = new Monolog_Logger('app');
        $syslog = new SyslogHandler("[SOA-MAILER.$client]", 'local0');
        $formatter = new LineFormatter("%level_name%: %message% %context%");
        $syslog->setFormatter($formatter);
        $log->pushHandler($syslog);

        return $log;
    }

}