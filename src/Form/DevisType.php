<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('dateVal', DateType::class, [
//                'label' => 'Date de Validité',
//                'required' => false
//            ])
            ->add('typeClient', ChoiceType::class, [
                'choices' => [
                    'Professionel' => 'Professionel',
                    'Particulier' => 'Particulier'
                ],

                'attr' => [
                'label' => 'Type de Client',
                'class' => 'form-control'
                ]
            ])
            ->add('service', ChoiceType::class, [
                'choices' => [
                    'Express' => 'Express',
                    'Standart' => 'Standart',
                ],

                'attr' => [
                'label' => 'Service',
                'class' => 'form-control'
                ]
            ])
            ->add('expediteur', TextType::class, [
                'attr' => [
                'label' => 'Adresse Expéditeur',
                'class' => 'form-control'
                ]
            ])
            ->add('destinataire', TextType::class, [
                'attr' => [
                'label' => 'Adresse Destinataire',
                'class' => 'form-control'
                ]
            ])
            ->add('marchandise', TextType::class, [
                'attr' => [
                'label' => 'Conditionnement',
                'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class,[
                'attr' => [
                    'label' => 'email',
                    'class' => 'form-control'
                ]

            ])
            ;

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
