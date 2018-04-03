<?php

namespace App\Command;

use App\Service\ClientManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClientCreateCommand extends Command
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
            ->setName('app:client:create')
            ->setDescription('Create new client')
            ->setHelp('This commend create new client which can send mail through this service.')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the client.')
            ->addArgument('alias', InputArgument::REQUIRED, 'Alias of the client.')
            ->addArgument('sender', InputArgument::REQUIRED, 'Sender email address of the client.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $_name = $input->getArgument('name');
        $_alias = $input->getArgument('alias');
        $_sender = $input->getArgument('sender');

        $ss = new SymfonyStyle($input, $output);
        $ss->title('Client Creator');
        $ss->section("Create new client $_name");

        $_allowIPs_string = $ss->ask(
                "Choose comma separated IP addresses which have access to this client OR leave empty for access from any IPs",
                null,
                function ($_ips) {
                    if (null === $_ips)
                        return null;
                    return $_ips;
                }
            );

        $_allowIPs = isset($_allowIPs_string) ? explode(",", $_allowIPs_string) : null;

        $client = $this->clientManager->create($_name, $_alias, $_sender, $_allowIPs);

        if ($client) {
            $output->writeln([
                'Client successfully generated.',
                '',
                'ID: ' . $client->getId(),
                'Name: ' . $_name,
                'Alias: ' . $_alias,
                'Sender: ' . $_sender,
                '',
                'Allow IPs: ' . (isset($_allowIPs) ? str_replace(",", " ", $_allowIPs_string) : 'any'),
                '',
                'Secret key: ' . $client->getClientSecret(),
                ''
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