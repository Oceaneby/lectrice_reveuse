<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\HttpFoundation\File\File;

class FileUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class)
            ->get('file')
            ->addModelTransformer(new CallbackTransformer(
                // Transformation pour transformer de File à String (par exemple chemin)
                function ($file) {
                    return $file ? $file->getPathname() : null;
                },
                // Transformation pour transformer de String à File (lorsque le formulaire est soumis)
                function ($string) {
                    return $string ? new File($string) : null;
                }
            ));
    }
}