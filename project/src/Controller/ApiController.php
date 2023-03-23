<?php

namespace App\Controller;

use App\Entity\Main\BlocUE;
use App\Entity\Main\BlocUECategory;
use App\Entity\Main\Parcours;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/parcours/{parcours}/bloc_ue', name: 'api_parours_bloc_ue', options: ['expose' => true])]
    public function index(Parcours $parcours): Response
    {
        $json = [];

        foreach ($parcours->getBlocUEs() as $blocUE) {
            $json[] = [
                'id' => $blocUE->getId(),
                'label' => $blocUE->getBlocUECategory(),
            ];
        }

        return $this->json($json);
    }

    #[Route('/api/bloc_ue/{blocUE}/ues', name: 'api_bloc_ue_ues', options: ['expose' => true])]
    public function ues(BlocUE $blocUE): Response
    {
        $json = [];

        foreach ($blocUE->getUEs() as $ue) {
            $json[] = [
                'id' => $ue->getId(),
                'label' => $ue->getLabel(),
            ];
        }

        return $this->json($json);
    }

    #[Route('/api/bloc_ue_category/{blocUECategory}/ues', name: 'api_bloc_ue_category_ues', options: ['expose' => true])]
    public function blocUECategoryUEs(BlocUECategory $blocUECategory): Response
    {
        $json = [];

        foreach ($blocUECategory->getUEs() as $ue) {
            $json[] = [
                'id' => $ue->getId(),
                'label' => $ue->getLabel(),
            ];
        }

        return $this->json($json);
    }
}

