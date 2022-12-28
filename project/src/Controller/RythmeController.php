<?php

namespace App\Controller;

use App\Entity\Rythme;
use App\Form\RythmeType;
use App\Repository\RythmeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/rythme')]
class RythmeController extends AbstractController
{
    #[Route('/', name: 'app_rythme_index', methods: ['GET'])]
    public function index(RythmeRepository $rythmeRepository): Response
    {
        return $this->render('rythme/index.html.twig', [
            'rythmes' => $rythmeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rythme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RythmeRepository $rythmeRepository): Response
    {
        $rythme = new Rythme();
        $form = $this->createForm(RythmeType::class, $rythme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rythmeRepository->save($rythme, true);

            return $this->redirectToRoute('app_rythme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rythme/new.html.twig', [
            'rythme' => $rythme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rythme_show', methods: ['GET'])]
    public function show(Rythme $rythme): Response
    {
        return $this->render('rythme/show.html.twig', [
            'rythme' => $rythme,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rythme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rythme $rythme, RythmeRepository $rythmeRepository): Response
    {
        $form = $this->createForm(RythmeType::class, $rythme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rythmeRepository->save($rythme, true);

            return $this->redirectToRoute('app_rythme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rythme/edit.html.twig', [
            'rythme' => $rythme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rythme_delete', methods: ['POST'])]
    public function delete(Request $request, Rythme $rythme, RythmeRepository $rythmeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rythme->getId(), $request->request->get('_token'))) {
            $rythmeRepository->remove($rythme, true);
        }

        return $this->redirectToRoute('app_rythme_index', [], Response::HTTP_SEE_OTHER);
    }
}
