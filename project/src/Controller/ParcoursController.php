<?php

namespace App\Controller;

use App\Entity\BlocUE;
use App\Entity\CampagneChoix;
use App\Entity\Parcours;
use App\Form\BlocUEType;
use App\Form\CampagneChoixType;
use App\Form\ParcoursType;
use App\Repository\BlocOptionRepository;
use App\Repository\BlocUERepository;
use App\Repository\CampagneChoixRepository;
use App\Repository\ParcoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/parcours')]
class ParcoursController extends AbstractController
{
    #[Route('/', name: 'app_parcours_index', methods: ['GET'])]
    public function index(ParcoursRepository $parcoursRepository): Response
    {
        return $this->render('parcours/index.html.twig', [
            'parcours' => $parcoursRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_parcours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ParcoursRepository $parcoursRepository): Response
    {
        $parcour = new Parcours();
        $form = $this->createForm(ParcoursType::class, $parcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parcoursRepository->save($parcour, true);

            return $this->redirectToRoute('app_parcours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('parcours/new.html.twig', [
            'parcour' => $parcour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_parcours_show', methods: ['GET'])]
    public function show(Parcours $parcour): Response
    {
        return $this->render('parcours/show.html.twig', [
            'parcour' => $parcour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_parcours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Parcours $parcour, ParcoursRepository $parcoursRepository): Response
    {
        $form = $this->createForm(ParcoursType::class, $parcour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parcoursRepository->save($parcour, true);

            return $this->redirectToRoute('app_parcours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('parcours/edit.html.twig', [
            'parcour' => $parcour,
            'form' => $form,
        ]);
    }

    // add bloc ue, with ajax
    #[Route('/{id}/bloc_ue/add', name: 'app_parcours_add_bloc_ue', methods: ['GET', 'POST'])]
    public function addBlocUe(Request $request, Parcours $parcours, BlocUERepository $blocUERepository): Response
    {
        $blocUE = new BlocUE();
        $blocUE->setParcours($parcours);
        $form = $this->createForm(BlocUEType::class, $blocUE);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blocUERepository->save($blocUE, true);

            return $this->render('parcours/bloc_ue/_list.html.twig', [
                'parcours' => $parcours,
                'bloc_ues' => $parcours->getBlocUEs(),
            ]);
        }

        return $this->render('parcours/bloc_ue/_form.html.twig', [
            'bloc_ue' => $blocUE,
            'form' => $form,
        ]);
    }

    // edit bloc ue, with ajax
    #[Route('/{id}/bloc_ue/edit/{blocUE}', name: 'app_parcours_edit_bloc_ue', methods: ['GET', 'POST'])]
    public function editBlocUe(Request $request, Parcours $parcours, BlocUERepository $blocUERepository, BlocUE $blocUE): Response
    {
        $form = $this->createForm(BlocUEType::class, $blocUE);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blocUERepository->save($blocUE, true);

            return $this->render('parcours/bloc_ue/_list.html.twig', [
                'parcours' => $parcours,
                'bloc_ues' => $parcours->getBlocUEs(),
            ]);
        }

        return $this->render('parcours/bloc_ue/_form.html.twig', [
            'bloc_ue' => $blocUE,
            'form' => $form,
        ]);
    }

    // delete bloc ue, with ajax
    #[Route('/{id}/bloc_ue/delete/{blocUE}', name: 'app_parcours_delete_bloc_ue', methods: ['GET', 'POST'])]
    public function deleteBlocUe(Request $request, Parcours $parcours, BlocUERepository $blocUERepository, BlocUE $blocUE): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blocUE->getId(), $request->request->get('_token'))) {
            $blocUERepository->remove($blocUE, true);

            return $this->render('parcours/bloc_ue/_list.html.twig', [
                'parcours' => $blocUE->getParcours(),
                'bloc_ues' => $blocUE->getParcours()->getBlocUEs(),
            ]);
        }

        return $this->render('parcours/bloc_ue/_delete_form.html.twig', [
            'parcours' => $parcours,
            'bloc_ue' => $blocUE,
        ]);
    }

    // Route pour créer une campagne de choix pour ce parcours
    #[Route('/{id}/campagne/add', name: 'app_parcours_campagne_add', methods: ['GET', 'POST'])]
    public function newCampagne(Request $request, Parcours $parcour, CampagneChoixRepository $campagneChoixRepository): Response
    {
        $campagne = new CampagneChoix();
        $campagne->setParcours($parcour);
        $form = $this->createForm(CampagneChoixType::class, $campagne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $campagneChoixRepository->save($campagne, true);

            return $this->redirectToRoute('app_parcours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('campagne_choix/new.html.twig', [
            'campagne' => $campagne,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_parcours_delete', methods: ['POST'])]
    public function delete(Request $request, Parcours $parcour, ParcoursRepository $parcoursRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $parcour->getId(), $request->request->get('_token'))) {
            $parcoursRepository->remove($parcour, true);
        }

        return $this->redirectToRoute('app_parcours_index', [], Response::HTTP_SEE_OTHER);
    }
}
