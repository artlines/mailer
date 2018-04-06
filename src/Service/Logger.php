<?php

namespace App\Service;

use App\Entity\Log;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger as Monolog_Logger;

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
     * @param $ipAddress
     * @param $isSend
     *
     * @return bool
     */
    public function logMail(\Swift_Message $sm, $ipAddress, $isSend)
    {
        $log = new Log();

        $_from = key($sm->getFrom());

        $log->setEmailFrom($_from);
        $log->setEmailTo($sm->getTo());
        $log->setEmailCc($sm->getCc());
        $log->setEmailBcc($sm->getBcc());

        $log->setMailSubject($sm->getSubject());
        $log->setMailBody($sm->getBody());

        $log->setIpAddress($ipAddress);
        $log->setIsSend($isSend);

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