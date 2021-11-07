<?php

namespace App\Command\Import;

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
    name: 'app:training:import',
    description: 'Import exercises from file',
)]
class TrainingImportCommand extends Command
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
            $repo = $this->entityManager->getRepository(Training::class);
            $entities = $repo->findAll();
            foreach ($entities as $entity) {
                $this->entityManager->remove($entity);
            }
            $this->entityManager->flush();
        }
        $content = file_get_contents($this->projectDir . "raw_data/training_example.json");
        if ($content) {
            $entities = json_decode($content, true);
            foreach ($entities as $jsonEntity) {
                $execStyle = new Training();
                $name = $jsonEntity['name'];
                $execStyle->setName($jsonEntity['name']);
                $execStyle->setRestBetweenCycles($jsonEntity['strainFactor']);

                $this->entityManager->persist($execStyle);
                $io->writeln("\tImporting $name...");
            }
            $this->entityManager->flush();
        }
        $io->success("Exercises imported.");

        return Command::SUCCESS;
    }
}
