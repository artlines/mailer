<?php

namespace App\Command;

use App\Service\DispatchManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DispatchSendCommand extends Command
{
    const STATUS_READY = 'ready';
    const STATUS_PROCESS = 'process';

    /**
     * @var DispatchManager $dispatchManager
     */
    private $dispatchManager;

    /**
     * CreateClientCommand constructor.
     * @param DispatchManager $dispatchManager
     */
    public function __construct(DispatchManager $dispatchManager)
    {
        $this->dispatchManager = $dispatchManager;

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
        $dispatches = $this->dispatchManager->getDispatches(self::STATUS_READY);

        foreach ($dispatches as $dispatch){
            $output->writeln([$dispatch->getSendList()->getEmails()]);
            /*$subject = $dispatch->getSubject();
            $from = $dispatch->getEmailFrom();
            $to = $dispatch->getEmailTo();
            $cc = $dispatch->getEmailCC();
            $bcc = $dispatch->getEmailBcc();
            $bodyData = [
                'body' => $dispatch->getTemplate()->getText(),
                'charset' => 'utf-8'
            ];*/
                $emails = explode(PHP_EOL, $dispatch->getSendList()->getEmails());
            foreach ($emails as $email){
                //
            }

        }

        $output->writeln(['Отправлено в очередь '. count($dispatches) .'рассылок']);

    }

}
