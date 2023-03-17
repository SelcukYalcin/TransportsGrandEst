<?php

namespace App\Form;

use App\Entity\Destinataire;
use App\Entity\Devis;
use App\Entity\Expediteur;
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

class DevisTrajetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse', TextType::class, [
                'attr' => [
                    'label' => 'Adresse Expéditeur',
                    'class' => 'form-control'
                ]
            ])
            ->add('codePostal', TextType::class, [
                'attr' => [
                    'label' => 'Code Postal',
                    'class' => 'form-control'
                ]
            ])
            ->add('ville', TextType::class, [
                'attr' => [
                    'label' => 'Ville',
                    'class' => 'form-control'
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expediteur::class,
        ]);
    }
}
