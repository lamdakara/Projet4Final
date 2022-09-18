<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscoveryController extends AbstractController
{
    // Gérer la vue
    #[Route('/decouvrirmaderotherapie', name: 'discovery')]
    public function index(): Response
    {
        return $this->render('discovery/index.html.twig');
    }
}
