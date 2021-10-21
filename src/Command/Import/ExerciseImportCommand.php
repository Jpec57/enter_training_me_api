<?php

namespace App\Command\Import;

use App\Entity\ExerciseReference;
use App\Entity\MuscleActivation;
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
    name: 'app:exercise:import',
    description: 'Import exercises from file',
)]
class ExerciseImportCommand extends Command
{
    use ImportCommandTrait;

    const columnNumber = 6;
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

        $this->entityManager->getConnection()->executeQuery("SET FOREIGN_KEY_CHECKS=0;");
        if ($input->getOption('reset')) {
            $repo = $this->entityManager->getRepository(ExerciseReference::class);
            $entities = $repo->findAll();
            foreach ($entities as $entity) {
                $this->entityManager->remove($entity);
            }
            $this->entityManager->flush();
        }
        $content = file_get_contents($this->projectDir . "raw_data/exercise_import.tsv");
        if ($content) {
            $cells = preg_split("/\t|\n/", $content);
            for ($i = ExerciseImportCommand::columnNumber; $i < count($cells); $i = $i + ExerciseImportCommand::columnNumber) {
                $name = $cells[$i];
                if (!empty($name)) {
                    $description = $cells[$i + 1];
                    $material = $this->handleListCell($cells[$i + 2]);
                    $muscleActivations = json_decode($cells[$i + 3]);
                    $strainessFactor = floatval($cells[$i + 4]);
                    $isBodyWeight = $cells[$i + 5] == 1;
                    $exo = new ExerciseReference();
                    $exo
                        ->setReference("CH1")
                        ->setName($name)
                        ->setDescription($description)
                        ->setMaterial($material)
                        ->setIsBodyweightExercise($isBodyWeight)
                        ->setStrainessFactor($strainessFactor);

                    if ($muscleActivations) {
                        foreach ($muscleActivations as $muscleActivationMap) {
                            $muscleActivation = new MuscleActivation();
                            $muscleActivation
                                ->setMuscle($muscleActivationMap->muscle)
                                ->setActivationRatio($muscleActivationMap->activationRatio);
                            $exo->addMuscleActivation($muscleActivation);
                        }
                    }
                    $this->entityManager->persist($exo);
                    $io->writeln("\tImporting $name...");
                }
            }
            $this->entityManager->flush();
        }
        $this->entityManager->getConnection()->executeQuery("SET FOREIGN_KEY_CHECKS=1;");

        $io->success("Exercises imported.");

        return Command::SUCCESS;
    }
}
