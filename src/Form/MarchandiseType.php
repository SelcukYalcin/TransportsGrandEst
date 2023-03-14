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
                'attr' => [
                    'label' => 'Conditionnement',
                    'class' => 'form-control'
                ]
            ])
            ->add('typeMarchandise', TextType::class, [
                'attr' => [
                    'label' => 'Type de marchandise',
                    'class' => 'form-control'
                ]
            ])
            ->add('qte', TextType::class, [
                'attr' => [
                    'label' => 'Quantité',
                    'class' => 'form-control'
                ]
            ])
            ->add('longueur', TextType::class, [
                'attr' => [
                    'label' => 'Longueur',
                    'class' => 'form-control'
                ]
            ])
            ->add('largeur', TextType::class, [
                'attr' => [
                    'label' => 'Largeur',
                    'class' => 'form-control'
                ]
            ])
            ->add('hauteur', TextType::class, [
                'attr' => [
                    'label' => 'Hauteur',
                    'class' => 'form-control'
                ]
            ])
            ->add('poids', TextType::class, [
                'attr' => [
                    'label' => 'Poids',
                    'class' => 'form-control'
                ]
            ])
//            ->add('expediteur')
//            ->add('destinataire')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Marchandise::class,
        ]);
    }
}
