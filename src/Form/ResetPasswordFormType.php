<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'mapped' => false,
                    'invalid_message' => 'le mot de passe doit être identique',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options' =>
                        [
                            'required' => true,
                            'label' => 'Mot de Passe ',
                            'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control'],
                            'constraints' =>
                                [
                                    new NotBlank(
                                        [
                                            'message' => 'Veillez entrer un mot de passe',
                                        ]),
                                    new Length(
                                        [
                                            'min' => 6,
                                            'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} charactères',
                                            // max length allowed by Symfony for security reasons
                                            'max' => 128,
                                        ]),
                                ],
                        ],
                    'second_options' =>
                        [
                            'attr' => ['class' => 'form-control',
                                'label' => 'Répétez le Mot de Passe ']
                        ]

                ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
