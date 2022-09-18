<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegalController extends AbstractController
{

    // Gérer la vue CGU
    #[Route('/cgu', name: 'legal_cgu')]
    public function cgu(): Response
    {
        return $this->render('legal/cgu.html.twig');
    }

    // Gérer la vue CGV
    #[Route('/cgv', name: 'legal_cgv')]
    public function cgv(): Response
    {
        return $this->render('legal/cgv.html.twig');
    }
}
