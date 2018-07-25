<?php

namespace App\Command;

use App\Service\DispatchManager;
use App\Service\ClientManager;
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

    /**
     * CreateClientCommand constructor.
     * @param DispatchManager $dispatchManager
     */
    public function __construct(DispatchManager $dispatchManager, ContainerInterface $container, ClientManager $clientManager)
    {
        $this->dispatchManager = $dispatchManager;
        $this->clientManager = $clientManager;
        $this->container = $container;

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


        /**
         * Получение всех рассылок со статусом "готова к отправке"
         * Сформировать письма и отправить в очередь
         * Если отправлена в очередь, сменить статус рассылки
         * Получать статусы отправленных рассылок (количество email)
         * По завершении менять статус
         */
        $client = $this->clientManager->findOneByAlias(self::CLIENT);
        $dispatches = $this->dispatchManager->getDispatches(self::STATUS_READY);
        $rmq = $this->container->get('old_sound_rabbit_mq.mail_producer');

        foreach ($dispatches as $dispatch) {
            $timestamp = time();
            $data = json_encode([
                'client_alias' => self::CLIENT,
                'timestamp' => $timestamp,
                'hash' => hash('sha256', $client->getClientSecret() . $timestamp . $client->getAlias()),
                'template_alias' => $dispatch->getTemplate()->getAlias(),
                'params' => ['title_page' => 'title'],
                'subject' => $dispatch->getSubject(),
                'send_to' => explode(PHP_EOL, $dispatch->getSendList()->getEmails()),
                'send_cc' => $dispatch->getEmailCc(),
                'send_bcc' => $dispatch->getEmailBcc(),
                'sender' => $dispatch->getEmailFrom(),
            ]);

            $rmq->publish($data, self::CLIENT, ['content_type' => 'application/json']);

            $output->writeln([$dispatch->getSendList()->getEmails()]);

        }

        $output->writeln(['Отправлено в очередь ' . count($dispatches) . 'рассылок']);

    }

}
