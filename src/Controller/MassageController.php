<?php

namespace App\Controller;

use App\Entity\Care;
use App\Form\CareType;
use App\Repository\CareRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/massage')]
class MassageController extends AbstractController
{
    #[Route('/', name: 'app_massage_index', methods: ['GET'])]
    public function index(CareRepository $massageRepository): Response
    {
        return $this->render('massage/index.html.twig', [
            'massages' => $massageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_massage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CareRepository $massageRepository): Response
    {
        $massage = new Care();
        $form = $this->createForm(CareType::class, $massage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $massageRepository->add($massage, true);

            return $this->redirectToRoute('app_massage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('massage/new.html.twig', [
            'massage' => $massage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_massage_show', methods: ['GET'])]
    public function show(Care $massage): Response
    {
        return $this->render('massage/show.html.twig', [
            'massage' => $massage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_massage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Care $massage, CareRepository $massageRepository): Response
    {
        $form = $this->createForm(CareType::class, $massage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $massageRepository->add($massage, true);

            return $this->redirectToRoute('app_massage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('massage/edit.html.twig', [
            'massage' => $massage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_massage_delete', methods: ['POST'])]
    public function delete(Request $request, Care $massage, CareRepository $massageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$massage->getId(), $request->request->get('_token'))) {
            $massageRepository->remove($massage, true);
        }

        return $this->redirectToRoute('app_massage_index', [], Response::HTTP_SEE_OTHER);
    }
}
