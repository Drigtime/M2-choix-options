<?php

namespace App\Controller;

use App\Entity\Main\BlocUE;
use App\Form\BlocUEType;
use App\Repository\BlocUERepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/bloc_ue')]
class BlocUEController extends AbstractController
{
    #[Route('/', name: 'app_bloc_ue_index', methods: ['GET'])]
    public function index(BlocUERepository $blocUERepository): Response
    {
        return $this->render('bloc_ue/index.html.twig', [
            'bloc_ues' => $blocUERepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bloc_ue_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BlocUERepository $blocUERepository): Response
    {
        $blocUE = new BlocUE();
        $form = $this->createForm(BlocUEType::class, $blocUE);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blocUERepository->save($blocUE, true);

            return $this->redirectToRoute('app_bloc_ue_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bloc_ue/new.html.twig', [
            'bloc_ue' => $blocUE,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bloc_ue_show', methods: ['GET'])]
    public function show(BlocUE $blocUE): Response
    {
        return $this->render('bloc_ue/show.html.twig', [
            'bloc_ue' => $blocUE,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bloc_ue_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlocUE $blocUE, BlocUERepository $blocUERepository): Response
    {
        $form = $this->createForm(BlocUEType::class, $blocUE);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blocUERepository->save($blocUE, true);

            return $this->redirectToRoute('app_bloc_ue_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bloc_ue/edit.html.twig', [
            'bloc_ue' => $blocUE,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bloc_ue_delete', methods: ['POST'])]
    public function delete(Request $request, BlocUE $blocUE, BlocUERepository $blocUERepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blocUE->getId(), $request->request->get('_token'))) {
            $blocUERepository->remove($blocUE, true);
        }

        return $this->redirectToRoute('app_bloc_ue_index', [], Response::HTTP_SEE_OTHER);
    }
}
