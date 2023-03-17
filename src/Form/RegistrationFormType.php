<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' =>[
                'label' => 'Email',
                'required' => true,
                'class' => 'form-control']
            ])
            ->add('agreeTerms', CheckboxType::class,
            [
                'label' => 'Conditions d\'utilisations',
                'mapped' => false,
                'constraints' =>
                [
                    new IsTrue(
                    [
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class,
            [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'le mot de passe doit être identique',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' =>
                [
                    'required' => true,
                    'label' => 'Mot de Passe',
                    'attr' => ['autocomplete' => 'new-password', 'class'=> 'form-control'],
                    'constraints' =>
                    [
                        new NotBlank(
                        [
                            'message' => 'Please enter a password',
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
                    'attr'=>[ 'class' =>'form-control',
                    'label' => 'Répétez le Mot de Passe']
                ],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
