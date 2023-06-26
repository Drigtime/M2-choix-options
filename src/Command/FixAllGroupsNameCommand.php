<?php

namespace App\Command;

use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\ParcoursRepository;
use App\Repository\UERepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fix-all-groups-name',
    description: 'Fix all groups name',
)]
class FixAllGroupsNameCommand extends Command
{
    public function __construct(private UERepository $UERepository,
                                private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $ues = $this->UERepository->findAll();

        foreach ($ues as $ue) {
            $groups = $ue->getGroupes();

            foreach ($groups as $index => $group) {
                $group->setLabel($index + 1);
            }
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
