<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Livraison;
use App\Form\MarchandiseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
            ->add('marchandise', CollectionType::class, [
                'entry_type' => MarchandiseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
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
