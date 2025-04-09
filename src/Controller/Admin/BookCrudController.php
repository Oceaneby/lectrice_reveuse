<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use App\Controller\Trait\Show;

class BookCrudController extends AbstractCrudController
{
    use Show;
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
           
            TextField::new('title'),
            TextareaField::new('description'),
            AssociationField::new('authors'),
            AssociationField::new('publisher'),
            ImageField::new('cover_image') // Ajouter le champ ImageField
                ->setBasePath('uploads/book_cover') // Définit le répertoire d'images
                ->setUploadDir('public/uploads/book_cover') // Définir le dossier de téléchargement
                ->setRequired(false), 
           
        ];
    }
    
}
