<?php

namespace App\Controller;

use App\Entity\Care;
use App\Repository\BookingRepository;
use App\Repository\CareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeetingController extends AbstractController
{
    // Récupération dans la BDD
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/rendezvous', name: 'meeting')]
    public function index(CareRepository $massageRepository): Response
    {
        $messages = $massageRepository->findAll();

        // Récupération des éléments soins dans la BDD
        $messages = $this->entityManager->getRepository(Care::class)->findAll();

        // Gérer la vue
        return $this->render('meeting/index.html.twig', [
            'messages' => $messages
        ]);
    }

    #[Route('/calendar', name: 'calendar')]
    public function calendar(BookingRepository $bookingRepository): Response
    {
        $bookings = $bookingRepository->findAll();

        $calendars = [];

        foreach($bookings as $booking) {
            $calendars [] = [
                'id' => $booking->getId(),
                'start' => $booking->getDebut()->format('Y-m-d H:i:s'),
                'end' => $booking->getFin()->format('Y-m-d H:i:s'),
                'title' => $booking->getTitre(),
            ];
        }

        return $this->render('meeting/calendar.html.twig', ['calendars' => json_encode($calendars)]);
    }
}

