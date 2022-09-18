<?php

namespace App\Command;

use App\Repository\BookingRepository;
use App\Service\MailjetService;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:rappel-rdv',
    description: 'Rappel tous les J7',
)]
class RappelRdvCommand extends Command
{
    private $bookingRepository;
    private $mailjetService;

    public function __construct(BookingRepository $bookingRepository, MailjetService $mailjetService)
    {
        $this->bookingRepository = $bookingRepository;
        $this->mailjetService = $mailjetService;

        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Lancement du check de rappel');


        $bookings = $this->bookingRepository->findAll();

        $maintenant = new \DateTime();

        $dateInferieurA7Jour = new \DateTime('+7 days');

        $sujet = 'Rappel RDV';
        $contenu = "Bonjour, n'oubliez pas votre RDV le : ";

        $total = 0; // total des nombre au quel on la envoyé un email
        foreach($bookings as $booking) {
            // on verifie que la date de reservation n'est pas dépasse et inferieur à 7 jours
            if (
                $booking->getDebut() >= $maintenant 
                && $booking->getDebut() <= $dateInferieurA7Jour
            ) {
                
                $total++;

                $user = $booking->getUser();

                $username = $user->getFirstname() .' '.$user->getLastname(); 
                $email = $user->getEmail();
                
                $contenu .= ' '.$booking->getDebut()->format('d/m/Y à h:i');

                $this->mailjetService->sendEmail($email, $username, $sujet, $contenu);
            }
        }

        $io->success($total . ' emails envoyés');

        return Command::SUCCESS;
    }
}
