<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,[
                'label' => 'Nom d\'utilisateur',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => "Le nom d'utilisateur ne peut pas être vide.",
                    ]),
                ],
                'label_attr' => [
                    'class' => 'block text-lg font-medium text-gray-700 mb-2',
                ]
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => "Le prénom ne peut pas être vide.",
                    ]),
                ],
                'empty_data' => '',
                'label_attr' => [
                    'class' => 'block text-lg font-medium text-gray-700 mb-2',
                    'placeholder' => '',
                ]
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'label_attr' => [
                    'class' => 'block text-lg font-medium text-gray-700 mb-2',
                    'placeholder' => '',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => "Email indisponible.",
                    ]),
                ],
                'label_attr' => [
                    'class' => 'block text-lg font-medium text-gray-700 mb-2',
                    'placeholder' => '',
                ]
            ])
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Ancien mot de passe',
                'required' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'current-password'],
                'label_attr' => [
                    'class' => 'block text-lg font-medium text-gray-700 mb-2',
                    'placeholder' => '',
                ]
                
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'required' => false,
                'mapped' => false,
                'constraints' => [ 
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label_attr' => [
                    'class' => 'block text-lg font-medium text-gray-700 mb-2',
                    'placeholder' => '',
                ]
            ])
         
           ->add('ConfirmPassword', PasswordType::class, [
            'label' => 'Confirmer le mot de passe',
            'mapped' => false,
            'required' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'label_attr' => [
                'class' => 'block text-lg font-medium text-gray-700 mb-2',
                'placeholder' => '',
            ]
           ])
           
            
            ->add('profilPicture', FileType::class, [
                'label' => 'Nouvelle photo de profil',
                'required' => false,
                'mapped' => false,
                'label_attr' => [
                    'class' => 'block text-lg font-medium text-gray-700 mb-2',   
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/webp',],
                        'mimeTypesMessage' => 'Merci de télécharger une image valide (WebP)',
                    ])
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
