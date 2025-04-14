<?php
// src/Command/TestObserverCommand.php
namespace App\Command;

use App\Entity\Conge;
use App\Entity\Rh;
use App\Entity\Typeconge;
use App\Observer\CongeSubject;
use App\Observer\NotificationSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestObserverCommand extends Command
{
    protected static $defaultName = 'app:test-observer';

    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->entityManager = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Test complet du workflow de demande/approbation de congé')
            ->setHelp('Simule la création d\'une demande par un employé et son approbation par un RH');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $rhRepo = $this->entityManager->getRepository(Rh::class);

        // 1. Récupération du TypeConge ID 1
        $typeConge = $this->entityManager->find(Typeconge::class, 1);

        if (!$typeConge) {
            $io->error('Aucun TypeConge trouvé avec ID 1');
            $io->text('Veuillez d\'abord créer un TypeConge dans la base');
            return Command::FAILURE;
        }


        // 1. Trouver un employé (ROLE_USER)
        $employe = $rhRepo->createQueryBuilder('r')
            ->where('r.roles LIKE :role')
            ->setParameter('role', '%ROLE_USER%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$employe) {
            $io->error('Aucun employé (ROLE_USER) trouvé dans la base');
            $io->text('Astuce : Créez d\'abord un utilisateur avec le rôle ROLE_USER');
            return Command::FAILURE;
        }

        // 2. Trouver un RH (ROLE_RH)
        $rh = $rhRepo->createQueryBuilder('r')
            ->where('r.roles LIKE :role')
            ->setParameter('role', '%ROLE_RH%')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$rh) {
            $io->error('Aucun responsable RH (ROLE_RH) trouvé dans la base');
            $io->text('Astuce : Créez d\'abord un utilisateur avec le rôle ROLE_RH');
            return Command::FAILURE;
        }

        $io->section('Acteurs du test');
        $io->table(
            ['Rôle', 'ID', 'Nom complet'],
            [
                ['Employé', $employe->getId(), $employe->getPrenom() . ' ' . $employe->getNom()],
                ['Responsable RH', $rh->getId(), $rh->getPrenom() . ' ' . $rh->getNom()]
            ]
        );

        // 3. Création de la demande de congé
        $conge = new Conge();
        $conge->setRh($employe)
            ->setTypeconge($typeConge) // <-- CE CHAMP MANQUAIT
            ->setDatedebut(new \DateTime('+1 week'))
            ->setDatefin(new \DateTime('+2 weeks'))
            ->setNbjour(5)
            ->setEtat('en_attente')
            ->setDatedemande(new \DateTime())
            ->setDescription('Congé test automatisé');

        $this->entityManager->persist($conge);
        $this->entityManager->flush();

        $io->section('1. Création de la demande');
        $io->text([
            'Nouveau congé créé :',
            sprintf('ID: %d', $conge->getId()),
            sprintf(
                'Période: du %s au %s',
                $conge->getDatedebut()->format('d/m/Y'),
                $conge->getDatefin()->format('d/m/Y')
            ),
            sprintf('État: %s', $conge->getEtat())
        ]);

        // 4. Initialisation de l'Observer
        $subject = new CongeSubject($conge);
        $subject->attach(new NotificationSubscriber($this->entityManager));

        // Notification de création
        $subject->demandeCreated();
        $io->text('Notification envoyée au RH');

        // 5. Simulation de l'approbation
        $io->section('2. Approbation RH');
        $conge->setEtat('accepte');
        $this->entityManager->flush();

        $subject->demandeAccepted(['approved_by' => $rh->getId(), 'approver_name' => $rh->getPrenom() . ' ' . $rh->getNom()]);
        $io->text('Notification d\'approbation envoyée à l\'employé');

        // 6. Résumé final
        // $io->success('Test complété avec succès');
        // $io->text('Vérifications à faire :');
        // $io->listing([
        //     sprintf('Table congé : vérifiez létat du congé ID %d', $conge->getId()),
        //     sprintf('Table notification : 2 entrées attendues (pour les IDs %d et %d)', $rh->getId(), $employe->getId()),
        //     'Fichier de logs : var/log/dev.log'
        // ]);

        return Command::SUCCESS;
    }
}