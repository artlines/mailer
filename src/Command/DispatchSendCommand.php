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
         */
        //$dispatches = $this->dispatchManager->getDispatches(STATUS_READY);

        $output->writeln(['Твоя команда работает!']);

    }

}
