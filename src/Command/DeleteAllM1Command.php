<?php

namespace App\Command;

use App\Repository\EtudiantRepository;
use App\Repository\ParcoursRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:delete-all-m1',
    description: 'Delete all etudiants in a M1 parcours',
)]
class DeleteAllM1Command extends Command
{
    public function __construct(
        private EtudiantRepository     $etudiantRepository,
        private ParcoursRepository     $parcoursRepository,
        private UserRepository         $userRepository,
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $parcours = $this->parcoursRepository->findBy(['anneeFormation' => 2]);
        $etudiants = $this->etudiantRepository->findBy(['parcours' => $parcours]);

        foreach ($etudiants as $etudiant) {
            $user = $this->userRepository->findOneBy(['email' => $etudiant->getMail()]);
            if ($user) {
                $this->userRepository->remove($user);
            }
            $this->etudiantRepository->remove($etudiant);
        }
        $this->entityManager->flush();

        $io->success('All M1 etudiants have been deleted.');

        return Command::SUCCESS;
    }
}
