<?php

namespace App\Command;

use App\Service\ClientManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateClientCommand extends Command
{
    /**
     * @var ClientManager $clientManager
     */
    private $clientManager;

    /**
     * CreateClientCommand constructor.
     * @param ClientManager $clientManager
     */
    public function __construct(ClientManager $clientManager)
    {
        $this->clientManager = $clientManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:create-client')
            ->setDescription('Create new client')
            ->setHelp('This commend create new client which can send mail through this service.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output
            ->writeln([
                'Client Creator',
                '==============',
                ''
            ]);

        $client = $this->clientManager->create('TestClient_'.rand(1,100), 'test-client-'.rand(1,100));

        $output->writeln([
            'Client secret key:',
            $client->getClientSecret()
        ]);
    }

}