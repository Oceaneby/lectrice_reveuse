<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Titre du livre'],
            ])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'fullName',
                'required' => false,
                'placeholder' => 'Auteur',
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,  
                'required' => false,
                'placeholder' => 'Genres',
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'bg-indigo-600 text-white rounded-lg py-2 px-4 transition hover:bg-indigo-700'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'method' => 'GET', 
        ]);
    }
}

