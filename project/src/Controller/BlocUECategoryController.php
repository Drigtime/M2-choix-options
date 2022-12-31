<?php

namespace App\Controller;

use App\Entity\BlocUECategory;
use App\Form\BlocUECategoryType;
use App\Repository\BlocUECategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/bloc_ue/category')]
class BlocUECategoryController extends AbstractController
{
    #[Route('/', name: 'app_bloc_ue_category_index', methods: ['GET'])]
    public function index(BlocUECategoryRepository $blocUETypeRepository): Response
    {
        return $this->render('bloc_ue_category/index.html.twig', [
            'bloc_u_e_categories' => $blocUETypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bloc_ue_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BlocUECategoryRepository $blocUETypeRepository): Response
    {
        $blocUECategory = new BlocUECategory();
        $form = $this->createForm(BlocUECategoryType::class, $blocUECategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blocUETypeRepository->save($blocUECategory, true);

            return $this->redirectToRoute('app_bloc_ue_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bloc_ue_category/new.html.twig', [
            'bloc_u_e_category' => $blocUECategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bloc_ue_category_show', methods: ['GET'])]
    public function show(BlocUECategory $blocUECategory): Response
    {
        return $this->render('bloc_ue_category/show.html.twig', [
            'bloc_u_e_category' => $blocUECategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bloc_ue_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlocUECategory $blocUECategory, BlocUECategoryRepository $blocUETypeRepository): Response
    {
        $form = $this->createForm(BlocUECategoryType::class, $blocUECategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blocUETypeRepository->save($blocUECategory, true);

            return $this->redirectToRoute('app_bloc_ue_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bloc_ue_category/edit.html.twig', [
            'bloc_u_e_category' => $blocUECategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bloc_ue_category_delete', methods: ['POST'])]
    public function delete(Request $request, BlocUECategory $blocUECategory, BlocUECategoryRepository $blocUETypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blocUECategory->getId(), $request->request->get('_token'))) {
            $blocUETypeRepository->remove($blocUECategory, true);
        }

        return $this->redirectToRoute('app_bloc_ue_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
