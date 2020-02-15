<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',
                TextType::class,
                [
                    'label' => 'nom'
                ]
            )

            ->add('lastName',
                TextType::class,
                [
                    'label' => 'prenom'
                ]
            )

            ->add('email',
                EmailType::class,
                [
                    'label' => 'Email'
                ]
            )

            ->add('plainPassword',
                // 2 champs qui doivent avoir la même valeur
                RepeatedType::class,
                [
                    // .. de type password
                    'type' => PasswordType::class,
                    // options du champ 1
                    'first_options' => [
                        'label' => 'Mot de passe',
                        'help' => 'Le mot de passe doit faire entre 6 et 20 caracteres et contenir au moins une majuscule et un chiffre'
                    ],
                    // option du champ 2
                    'second_options' => [
                        'label' => 'Confirmation du mot de passe'
                    ],
                    // message si les 2 champs n'ont pas la même valeur
                    'invalid_message' => 'La confirmation ne correspond pas au mot de passe'
                ]
            )


            ->add('avatar',
                FileType::class,
                [
                    'label' => 'Avatar',
                    // champ optionnel
                    'required' => false
                ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
