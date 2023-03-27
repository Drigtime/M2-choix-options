<?php

namespace App\Controller;

use App\Entity\Main\AnneeFormation;
use App\Form\AnneeFormationType;
use App\Repository\AnneeFormationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/annee_formation')]
class AnneeFormationController extends AbstractController
{
    #[Route('/', name: 'app_annee_formation_index', methods: ['GET'])]
    public function index(AnneeFormationRepository $anneeFormationRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $anneeFormationRepository->createQueryBuilder('a');

        $annee_formations = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('annee_formation/index.html.twig', [
            'annee_formations' => $annee_formations,
        ]);
    }

    #[Route('/new', name: 'app_annee_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnneeFormationRepository $anneeFormationRepository): Response
    {
        $anneeFormation = new AnneeFormation();
        $form = $this->createForm(AnneeFormationType::class, $anneeFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $anneeFormationRepository->save($anneeFormation, true);

            return $this->redirectToRoute('app_annee_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annee_formation/new.html.twig', [
            'annee_formation' => $anneeFormation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annee_formation_show', methods: ['GET'])]
    public function show(AnneeFormation $anneeFormation): Response
    {
        return $this->render('annee_formation/show.html.twig', [
            'annee_formation' => $anneeFormation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annee_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AnneeFormation $anneeFormation, AnneeFormationRepository $anneeFormationRepository): Response
    {
        $form = $this->createForm(AnneeFormationType::class, $anneeFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $anneeFormationRepository->save($anneeFormation, true);

            return $this->redirectToRoute('app_annee_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annee_formation/edit.html.twig', [
            'annee_formation' => $anneeFormation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annee_formation_delete', methods: ['POST'])]
    public function delete(Request $request, AnneeFormation $anneeFormation, AnneeFormationRepository $anneeFormationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $anneeFormation->getId(), $request->request->get('_token'))) {
            $anneeFormationRepository->remove($anneeFormation, true);
        }

        return $this->redirectToRoute('app_annee_formation_index', [], Response::HTTP_SEE_OTHER);
    }
}
