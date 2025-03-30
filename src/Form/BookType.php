<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use App\Entity\Publisher;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class BookType extends AbstractType
{
   

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', null, [
            'attr' => [
                'class' => 'block w-full sm:w-3/4 p-4 mt-2 mb-4 ml-4 border border-gray-300 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all duration-300'
            ]
        ])
        ->add('description', null, [
            'attr' => [
                'class' => 'block w-full sm:w-3/4 p-4 mt-2 mb-4 ml-4 border border-gray-300 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all duration-300'
            ]
        ])
        ->add('isbn', null, [
            'attr' => [
                'class' => 'block w-full sm:w-3/4 p-4 mt-2 mb-4 ml-4 border border-gray-300 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all duration-300'
            ]
        ])
        ->add('publication_date', DateType::class, [
            'widget' => 'single_text',
            'html5' => true,
            'attr' => [
                'class' => 'block w-full sm:w-3/4 p-4 mt-2 mb-4 ml-4 border border-gray-300 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all duration-300'
            ]
        ])
        ->add('amazonUrl', TextType::class, [
            'required' => false,
            'label' => 'Amazon URL',
            'attr' => [
                'class' => 'block w-full sm:w-3/4 p-4 mt-2 mb-4 ml-4 border border-gray-300 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all duration-300'
            ]
        ])
        ->add('fnacUrl', TextType::class, [
            'required' => false,
            'label' => 'Fnac URL',
            'attr' => [
                'class' => 'block w-full sm:w-3/4 p-4 mt-2 mb-4 ml-4 border border-gray-300 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all duration-300'
            ]
        ])
        ->add('cover_image', FileType::class, [
            'attr' => [
                'class' => 'block w-full sm:w-3/4 p-4 mt-2 mb-4 ml-4 border border-gray-300 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all duration-300'
            ],
            'constraints' => [
                new Image([
                    'mimeTypes' => ['image/jpeg', 'image/png'],
                    'mimeTypesMessage' => 'Merci de télécharger une image au bon format'
                ])
            ],
            'required' => false,
            'data_class' => null,
        ])
        ->add('authors', EntityType::class, [
            'class' => Author::class,
            'choice_label' => 'first_name',
            'multiple' => true,
            'expanded' => true,
            'attr' => [
                'class' => 'block w-full sm:w-3/4 p-4 mt-2 mb-4 ml-4 border border-gray-300 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all duration-300'
            ]
        ])
        ->add('genres', EntityType::class, [
            'class' => Genre::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
            'attr' => [
                'class' => 'block w-full sm:w-3/4 p-4 mt-2 mb-4 ml-4 border border-gray-300 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all duration-300'
            ]
        ])
        ->add('publisher', EntityType::class, [
            'class' => Publisher::class,
            'choice_label' => 'name',
            'attr' => [
                'class' => 'block w-full sm:w-3/4 p-4 mt-2 mb-4 ml-4 border border-gray-300 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 transition-all duration-300'
            ]
        ]);

    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
