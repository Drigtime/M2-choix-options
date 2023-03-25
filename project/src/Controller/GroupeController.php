<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Etudiant;
use App\Form\GroupeType;
use App\Repository\GroupeRepository;
use App\Repository\EtudiantRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\MoveGroupeEtudiantType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/groupe')]
class GroupeController extends AbstractController
{
    #[Route('/', name: 'app_groupe_index', methods: ['GET'])]
    public function index(GroupeRepository $groupeRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $groupeRepository->createQueryBuilder('cc');
        $queryBuilder->leftJoin('cc.ue', 'ccue');

        $groupes = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('groupe/index.html.twig', [
            'groupes' => $groupes
        ]);
    }

    #[Route('/new', name: 'app_groupe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GroupeRepository $groupeRepository): Response
    {
        $groupe = new Groupe();
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupeRepository->save($groupe, true);

            return $this->redirectToRoute('app_groupe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('groupe/new.html.twig', [
            'groupe' => $groupe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_groupe_show', methods: ['GET'])]
    public function show(Groupe $groupe): Response
    {
        return $this->render('groupe/show.html.twig', [
            'groupe' => $groupe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_groupe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Groupe $groupe, GroupeRepository $groupeRepository): Response
    {
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupeRepository->save($groupe, true);

            return $this->redirectToRoute('app_groupe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('groupe/edit.html.twig', [
            'groupe' => $groupe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_groupe_delete', methods: ['POST'])]
    public function delete(Request $request, Groupe $groupe, GroupeRepository $groupeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $groupe->getId(), $request->request->get('_token'))) {
            $groupeRepository->remove($groupe, true);
        }

        return $this->redirectToRoute('app_groupe_index', [], Response::HTTP_SEE_OTHER);
    }

    //Change l'étudiant de groupe
    #[Route('/groupe/move_groupe_student/{id}', name: 'app_groupe_move_student')]
    public function moveStudent(Groupe $groupe, Request $request, EtudiantRepository $etudiantRepository, GroupeRepository $groupeRepository): Response
    {
        $form = $this->createForm(MoveGroupeEtudiantType::class, $groupe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupeRepository->save($groupe, true);

            $groupes = $groupeRepository->findAll();
            return $this->render('groupe/index.html.twig', [
                'groupes' => $groupes,
            ]);
        }

        return $this->render('groupe/move_groupe_etudiant.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
