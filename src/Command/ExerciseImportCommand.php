<?php

namespace App\Command;

use App\Entity\ExerciseReference;
use App\Entity\MuscleActivation;
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
        // $this
        //     ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //     ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        // ;
    }


    private function handleListCell($cellContent, $delimiter = ","): array
    {
        return array_map(function ($element) {
            return trim($element);
        }, explode($delimiter, $cellContent));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $io->write($this->projectDir . "exercise_import.txt");

        $content = file_get_contents($this->projectDir . "exercise_import.txt");
        if ($content) {
            $cells = preg_split("/\t|\n/", $content);
            for ($i = ExerciseImportCommand::columnNumber; $i < count($cells); $i = $i + ExerciseImportCommand::columnNumber) {
                $name = $cells[$i];

                $description = $cells[$i + 1];
                $material = $this->handleListCell($cells[$i + 2]);
                $muscleActivations = json_decode($cells[$i + 3], true);
                $strainessFactor = floatval($cells[$i + 4]);
                $exo = new ExerciseReference();
                $exo
                    //TODO
                    ->setReference("CH1")
                    ->setName($name)
                    ->setDescription($description)
                    ->setMaterial($material)
                    ->setStrainessFactor($strainessFactor);
                if ($muscleActivations) {
                    foreach ($muscleActivations as $muscleActivationMap) {
                        $muscleActivation = new MuscleActivation();
                        $muscleActivation
                            ->setMuscle($muscleActivationMap["muscle"])
                            ->setActivationRatio($muscleActivationMap["activationRatio"]);
                        $exo->addMuscleActivation($muscleActivation);
                    }
                }
                $this->entityManager->persist($exo);
            }
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}
