<?php

namespace App\Command\Import;

use App\Traits\ImportCommandTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:ref:import',
    description: 'Import all of the reference data',
)]
class RefImportCommand extends Command
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addOption('reset', null, InputOption::VALUE_NONE, 'Reset database before inserting');
    }

    private function runCommand(OutputInterface $output, string $cmd, array $args = [])
    {
        $command = $this->getApplication()->find($cmd);
        $cmdInput = new ArrayInput($args);
        return $command->run($cmdInput, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        if ($input->getOption('reset')) {
            $res = 0;
            $res += $this->runCommand($output, 'doctrine:database:drop', [
                '--force'  => true,
            ]);
            $res += $this->runCommand($output, 'doctrine:database:create');
            $res += $this->runCommand($output, 'doctrine:migrations:migrate', [
                '--no-interaction' => true
            ]);
            if ($res === 0) {
                $io->success('Database successfully reset.');
            }
        }

        $this->runCommand($output, 'app:exercise:import');

        return Command::SUCCESS;
    }
}
