<?php

namespace App\Command;

use App\Entity\Dispatch;
use App\Service\DispatchManager;
use App\Service\ClientManager;
use App\Service\Logger;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DispatchSendCommand extends Command
{
    const STATUS_READY = 'ready';
    const STATUS_PROCESS = 'process';
    const CLIENT = 'mailer';

    /**
     * @var DispatchManager $dispatchManager
     */
    private $dispatchManager;

    /**
     * @var ContainerInterface $container
     */
    private $container;
    private $clientManager;
    private $logger;

    /**
     * CreateClientCommand constructor.
     * @param DispatchManager $dispatchManager
     */
    public function __construct(
        DispatchManager $dispatchManager,
        ContainerInterface $container,
        ClientManager $clientManager,
        Logger $logger
    )
    {
        $this->dispatchManager = $dispatchManager;
        $this->clientManager = $clientManager;
        $this->container = $container;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:dispatch:send')
            ->setDescription('Command checkes status of dispatches and make dispatch if that datetime is equally with current');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @var ProducerInterface $rmq
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $ss = new SymfonyStyle($input, $output);
        $ss->title('Dispatches sender');
        $ss->section("Check ready dispatches");

        $client = $this->clientManager->findOneByAlias(self::CLIENT);
        $dispatches = $this->dispatchManager->getDispatches([self::STATUS_READY,self::STATUS_PROCESS]);
        $rmq = $this->container->get('old_sound_rabbit_mq.mail_producer');

        foreach ($dispatches as $dispatch) {
            $email_to = explode(PHP_EOL, $dispatch->getSendList()->getEmails());

            //если рассылка уже была в отправке и не завершена, смотрим логи и по каким адресам письма ещё не ушли
            if ($dispatch->getStatus()->getAlias() == self::STATUS_PROCESS){
                $email_to = $this->dispatchManager->getUndeliveredEmails($dispatch);
                $this->logger->syslog('')->debug("Неотправленные адреса " . print_r($email_to, true) . " рассылок mailer soa");
            }else{
                //все адреса в лог со статусом false
                $this->dispatchManager->setDispatchLog($dispatch, $email_to);
            }

            $cc =  !empty($dispatch->getEmailCc()) ? explode(',', $dispatch->getEmailCc()) : [];
            $bcc = !empty($dispatch->getEmailBcc()) ? explode(',', $dispatch->getEmailBcc()) : [];

            $timestamp = time();
            $data = json_encode([
                'client_alias' => self::CLIENT,
                'timestamp' => $timestamp,
                'hash' => hash('sha256', $client->getClientSecret() . $timestamp . $client->getAlias()),
                'template_alias' => $dispatch->getTemplate()->getAlias(),
                'params' => [
                    'title_page' => $dispatch->getSubject(),
                    'name_from' => $dispatch->getNameFrom(),
                    'email_from' => $dispatch->getEmailFrom()
                ],
                'subject' => $dispatch->getSubject(),
                'send_to' => $email_to,
                'send_cc' => $cc,
                'send_bcc' => $bcc,
                'sender' => $dispatch->getEmailFrom(),
                'dispatch_id' => $dispatch->getId(),
            ]);

            $rmq->publish($data, '', ['content_type' => 'application/json']);
            $this->logger->syslog('')->debug("Отправлено в очередь " . count($dispatches) . " рассылок mailer soa");
            $status = $this->dispatchManager->setDispatchStatus(self::STATUS_PROCESS, $dispatch);
            $this->logger->syslog('')->debug("Статус рассылки " . $dispatch->getId() . " изменён на " . $status->getName());
        }
    }
}
