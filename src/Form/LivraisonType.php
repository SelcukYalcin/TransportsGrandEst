<?php

namespace App\Form;

use App\Entity\Livraison;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('expediteur', TextType::class)
            ->add('destinataire', TextType::class)
            ->add('membre', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
//                 'multiple' => true,
//                 'expanded' => true,
            ])
            ->add('serviceLivraison', ChoiceType::class, [
                'choices' => [
                    'Express' => true,
                    'Standard' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'btn btn-info'
                ]
            ])
            ->add('dateEnlevement', DateTimeType::class)
            ->add('dateLivree', DateTimeType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}
