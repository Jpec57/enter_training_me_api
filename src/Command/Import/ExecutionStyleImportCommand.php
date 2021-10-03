<?php

namespace App\Command\Import;

use App\Entity\ExecutionStyle;
use App\Entity\ExerciseReference;
use App\Entity\MuscleActivation;
use App\Entity\Training;
use App\Traits\ImportCommandTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:executionStyle:import',
    description: 'Import execution style from file',
)]
class ExecutionStyleImportCommand extends Command
{
    use ImportCommandTrait;

    const columnNumber = 5;
    private string $projectDir;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManagerInterface, string $projectDir)
    {

        parent::__construct();
        $this->projectDir = $projectDir . "/";
        $this->entityManager = $entityManagerInterface;
    }

    protected function configure(): void
    {
        $this
            ->addOption('reset', null, InputOption::VALUE_NONE, 'Reset entities');
    }




    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('reset')) {
            $repo = $this->entityManager->getRepository(ExecutionStyle::class);
            $entities = $repo->findAll();
            foreach ($entities as $entity) {
                $this->entityManager->remove($entity);
            }
            $this->entityManager->flush();
        }
        $content = file_get_contents($this->projectDir . "raw_data/default_execution_style.json");
        if ($content) {
            $styles = json_decode($content, true);
            foreach ($styles as $style) {
                $execStyle = new ExecutionStyle();
                $name = $style['name'];
                $execStyle->setName($style['name']);
                $execStyle->setStrainFactor($style['strainFactor']);
                $execStyle->setDescription($style['description']);

                $this->entityManager->persist($execStyle);
                $io->writeln("\tImporting $name...");
            }
            $this->entityManager->flush();
        }
        $io->success("ExecutionStyles imported.");

        return Command::SUCCESS;
    }
}
