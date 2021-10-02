<?php

namespace App\Command\Import;

use App\Entity\ExerciseFormat;
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
    name: 'app:exerciseFormat:import',
    description: 'Import exercises from file',
)]
class ExerciseFormatImportCommand extends Command
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
            $repo = $this->entityManager->getRepository(ExerciseFormat::class);
            $entities = $repo->findAll();
            foreach ($entities as $entity) {
                $this->entityManager->remove($entity);
            }
            $this->entityManager->flush();
        }
        $content = file_get_contents($this->projectDir . "training_import.txt");
        if ($content) {
            $cells = preg_split("/\t|\n/", $content);
            for ($i = ExerciseFormatImportCommand::columnNumber; $i < count($cells); $i = $i + ExerciseFormatImportCommand::columnNumber) {
                $name = $cells[$i];
                // $description = $cells[$i + 1];
                // $material = $this->handleListCell($cells[$i + 2]);
                // $muscleActivations = json_decode($cells[$i + 3], true);
                // $strainessFactor = floatval($cells[$i + 4]);

                $training = new ExerciseFormat();

                $this->entityManager->persist($training);
                $io->writeln("\tImporting $name...");
            }
            $this->entityManager->flush();
        }
        $io->success("Exercises imported.");

        return Command::SUCCESS;
    }
}
