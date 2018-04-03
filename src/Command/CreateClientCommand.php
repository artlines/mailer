<?php

namespace App\Command;

use App\Service\ClientManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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
            ->setHelp('This commend create new client which can send mail through this service.')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the client.')
            ->addArgument('alias', InputArgument::REQUIRED, 'Alias of the client.');
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

        $_name = $input->getArgument('name');
        $_alias = $input->getArgument('alias');

        $client = $this->clientManager->create($_name, $_alias);

        if ($client) {
            $output->writeln([
                'Client successfully generated.',
                '',
                'ID: ' . $client->getId(),
                'Name: ' . $_name,
                'Alias: ' . $_alias,
                'Secret key: ' . $client->getClientSecret()
            ]);
        }
        else
        {
            $output->writeln([
                'Error',
                ''
            ]);
        }
    }

}