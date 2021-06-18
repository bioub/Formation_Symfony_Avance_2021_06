<?php

namespace Romain\MyCommandBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;
use Nelmio\Alice\Loader\SimpleFileLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FixturesLoadCommand extends Command
{
    protected static $defaultName = 'fixtures:load';
    protected static $defaultDescription = 'Rempli la base de données avec un fichier Alice';

    /** @var SimpleFileLoader */
    protected $simpleFileLoader;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var UserPasswordHasherInterface */
    protected $passwordHasher;

    /**
     * FixturesLoadCommand constructor.
     * @param SimpleFileLoader $simpleFileLoader
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(SimpleFileLoader $simpleFileLoader, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->simpleFileLoader = $simpleFileLoader;
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('fixturePath', InputArgument::REQUIRED, 'Le chemin vers le fichier de fixture')
            ->addOption('truncate', 't', InputOption::VALUE_NONE, 'Vide la table avant d\'insérer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $fixturePath = $input->getArgument('fixturePath');

        $output->writeln('<bg=green;fg=white;options=bold,underscore>Bienvenue dans notre commande</>');

        $truncate = false;

        if (!$input->getOption('truncate')) {
            $question = new ConfirmationQuestion('Voulez-vous supprimer les enregistrements existants ? [y/N] ', false, '/^y/i');
            $truncate = $this->getHelper('question')->ask($input, $output, $question);
        }

        if ($truncate) {
            // injecter EventDispatcher
            // Créer une classe qui hérite de Event
            // DatabaseTruncateEvent
            // et le dispatcher ici
            // https://symfony.com/doc/current/components/event_dispatcher.html

            // depuis address-book-sf5
            // créer le event subscriber correspondant
            // injecter Logger et logger un message dans ce cas
            // make:subscriber

            $output->writeln('<bg=yellow;options=bold,underscore>Suppression des enregistrements</>');

            $dropCommand = $this->getApplication()->find('doctrine:schema:drop');
            $dropCommand->run(new ArrayInput(['--force' => true]), $output);

            $createCommand = $this->getApplication()->find('doctrine:schema:create');
            $createCommand->run(new ArrayInput([]), $output);
        }

        $objectSet = $this->simpleFileLoader->loadFile($fixturePath);

        foreach ($objectSet->getObjects() as $object) {
            // TODO trouver une meilleure implémentation
            // créer notre propre provider au niveau Alice pour accéder à un fonction
            // Alice qui dépend de UserPasswordHasherInterface
            if (method_exists($object, 'setPassword')) {
                $object->setPassword($this->passwordHasher->hashPassword($object, $object->getPassword()));
            }
            $this->em->persist($object);
        }

        $this->em->flush();

        $io->success('Les enregistrements ont bien été insérés.');

        return Command::SUCCESS;
    }
}
