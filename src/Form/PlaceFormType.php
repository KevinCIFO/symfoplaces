<?php

namespace App\Form;

use App\Entity\Place;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints\File;

class PlaceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, array('label' => 'Nombre del lugar'))
            ->add('descripcion', TextareaType::class, array('label' => 'Descripción del lugar'), ['attr' => ['style' => 'height: 100px']])
            ->add('valoracion', NumberType::class, [
                'label' => 'Valoración',
                'required' => false,
                'html5' => true
            ])
            ->add('pais', TextType::class, array('label' => 'País'))
            ->add('tipo', TextType::class, array('label' => 'Tipo de lugar'))
            ->add('Guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-success my-3']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
