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

            ->add('email', EmailType::class,[
                'attr' => [
                    'label' => 'email',
                    'class' => 'form-control'
                ]
            ])

            ->add('expediteur', ExpediteurType::class)
            ->add('destinataire', DestinataireType::class)
            ->add('marchandise', CollectionType::class, [
                'entry_type' => MarchandiseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ])
            ->add('clientType', ChoiceType::class, [
                'choices'  => [
                    'Professionnel' => true,
                    'Particulier' => false,
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('serviceType', ChoiceType::class, [
                'choices'  => [
                    'Express' => true,
                    'Standard' => false,
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('societe', TextType::class, [
                'required' => false
            ])
            ->add('nom', TextType::class, [
            ])
            ->add('prenom', TextType::class, [
            ])
            ->add('telephone', TelType::class, [
                'required' => false
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
