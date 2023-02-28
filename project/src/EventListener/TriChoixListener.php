<?php

namespace App\EventListener;

use App\Repository\ChoixRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;

class TriChoixListener
{
    public function triChoixes(FormEvent $event): void
    {
        $form = $event->getForm();
        $choixes = $form->get('choixes')->getData();

        if (!is_null($choixes)) {
            usort($choixes, function($a, $b) {
                return $a->getOrdre() - $b->getOrdre();
            });

            $form->get('choixes')->setData($choixes);
        }
    }
}