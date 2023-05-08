<?php

namespace App\Form;

use App\Entity\Marchandise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarchandiseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('conditionnement', TextType::class, [
                'label' => 'Conditionnement',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('typeMarchandise', TextType::class, [
                'label' => 'Type de marchandise',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('qte', TextType::class, [
                'label' => 'Quantité',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('longueur', TextType::class, [
                'label' => 'Longueur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('largeur', TextType::class, [
                'label' => 'Largeur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('hauteur', TextType::class, [
                'label' => 'Hauteur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('poids', TextType::class, [
                'label' => 'Poids',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Marchandise::class,
        ]);
    }
}
