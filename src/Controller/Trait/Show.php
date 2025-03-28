<?php

namespace App\Controller\Trait;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

trait Show
{
    public function configureActions(Actions $actions): Actions
    {
        // Ajouter l'action "DETAIL" sur la page d'index
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);

        // Personnaliser l'action "DETAIL" avec une icône Font Awesome
        $actions->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
            return $action->setIcon('fa fa-eye')->setLabel('Voir Détail'); // Ajouter une icône "eye" et un label personnalisé
        });

        // Personnaliser l'action "Créer" (PAGE_NEW) sur la page d'index
        $actions->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
            return $action->setIcon('fa fa-plus')->setLabel('Créer'); // Icône et label personnalisés
        });

        // Personnaliser l'action "Modifier" (PAGE_EDIT) sur la page d'index
        $actions->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action->setIcon('fa fa-pencil-alt')->setLabel('Modifier'); // Icône et label personnalisés
        });

        // Personnaliser l'action "Supprimer" (PAGE_DELETE) sur la page d'index
        $actions->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
            return $action->setIcon('fa fa-trash-alt')->setLabel('Supprimer'); // Icône et label personnalisés
        });

        return $actions; // Retourner les actions modifiées
    }
}