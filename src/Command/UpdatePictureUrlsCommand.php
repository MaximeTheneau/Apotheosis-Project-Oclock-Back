<?php

namespace App\Command;

use App\Entity\Recipe;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(name: 'app:update-picture-urls')]
class UpdatePictureUrlsCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $recipes = $this->entityManager->getRepository(Recipe::class)->findAll();
        foreach ($recipes as $recipe) {
            $picture = $recipe->getPicture(); // Récupère l'URL de l'image
            $newUrl = str_replace('https://ssss/', 'https://back.omiam-preprod.fr/', $picture);
            $recipe->setPicture($newUrl); // Met à jour l'URL de l'image
        }


        $this->entityManager->flush();

        $output->writeln('Picture URLs updated successfully.');

        return Command::SUCCESS;
    }
}