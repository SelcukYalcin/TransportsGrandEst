<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\Expediteur;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
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
            ->add('clientType', ChoiceType::class, [
//                'label' => 'Vous êtes un',
                'choices' => [
                    'Professionnel' => true,
                    'Particulier' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'btn btn-info',
                ],
                'row_attr' => [
                    'class' => '',
                ],
                'label_attr' => [
                    'class' => 'radio',
                ]
            ])
            ->add('serviceType', ChoiceType::class, [
                'label' => 'Service',
                'choices' => [
                    'Express' => true,
                    'Standard' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'btn btn-info',
                ],
                'row_attr' => [
                    'class' => '',
                ],
                'label_attr' => [
                    'class' => 'radio',
                ]
            ])
            ->add('expediteur', ExpediteurType::class, [
                'label' => 'Adresse d\'enlèvement',
                'label_attr' => [
                    'class' => 'radio',
                ]
            ])
            ->add('destinataire', DestinataireType::class, [
                'label' => 'Adresse de livraison',
                'label_attr' => [
                    'class' => 'radio',
                ]
            ])
            ->add('marchandise', CollectionType::class, [
                'entry_type' => MarchandiseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('societe', TextType::class, [
                'required' => false,
                'label' => 'Société',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('telephone', TelType::class, [
                'required' => false,
                'label' => 'Téléphone',
                'attr' => [
                    'class' => 'form-control',

                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
