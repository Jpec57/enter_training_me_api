<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:exercise:import',
    description: 'Add a short description for your command',
)]
class ExerciseImportCommand extends Command
{
    const columnNumber = 5;
    private string $projectDir;

    public function __construct(string $projectDir)
    {

        parent::__construct();
        $this->projectDir = $projectDir . "/";
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

    private function handleMap($cellContent, $delimiter = ","): array
    {
        return array_map(function ($element) {
            $value = trim($element);

            return [];
        }, explode($delimiter, $cellContent));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $io->write($this->projectDir . "exercise_import.txt");


        $content = file_get_contents($this->projectDir . "exercise_import.txt");
        if ($content) {
            $cells = preg_split("/\t|\n/", $content);
            for ($i = 0; $i < count($cells); $i = $i + ExerciseImportCommand::columnNumber) {
                $name = $cells[$i];
                $description = $cells[$i + 1];
                $material = $this->handleListCell($cells[$i + 2]);
                $muscleActivations = json_decode($cells[$i + 3], true);
                $strainessFactor = $cells[$i + 4];
                var_dump($muscleActivations);
            }
        }

        return Command::SUCCESS;
    }
}
