<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    // Gérer la vue
    #[Route('/prestationsettarifs', name: 'services')]
    public function index(): Response
    {
        return $this->render('services/index.html.twig');
    }
}
