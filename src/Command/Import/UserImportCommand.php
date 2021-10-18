<?php

namespace App\Command\Import;

use App\Entity\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:user:import',
    description: 'Add a short description for your command',
)]
class UserImportCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManagerInterface, string $projectDir)
    {

        parent::__construct();
        $this->entityManager = $entityManagerInterface;
        $this->passwordEncoder = $passwordEncoder;
    }


    protected function configure(): void
    {
        $this
            ->addOption('reset', null, InputOption::VALUE_NONE, 'Reset entities');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('reset')) {
            $repo = $this->entityManager->getRepository(User::class);
            $entities = $repo->findAll();
            foreach ($entities as $entity) {
                $this->entityManager->remove($entity);
            }
            $this->entityManager->flush();
        }

        $testUser = new User();
        $testUser->setEmail("test@jpec.fr")
            ->setPassword($this->passwordEncoder->hashPassword($testUser, "test"))
            // ->setFitnessProfile()
            ->setUsername("Jpec91");
        $this->entityManager->persist($testUser);
        $this->entityManager->flush();
        return Command::SUCCESS;
    }
}
