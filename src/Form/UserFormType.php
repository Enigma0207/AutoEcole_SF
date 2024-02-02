<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom et le nom sont requis.']),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le prénom et le nom doivent comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom et le nom ne peuvent pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('lastname', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom et le nom sont requis.']),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le prénom et le nom doivent comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom et le nom ne peuvent pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('phone', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'votre numéro est requis.']),
                    new Assert\Length([
                        'min' => 5,
                        'max' => 10,
                        'minMessage' => 'votre numéro doit comporter au moins {{ limit }} caractères.',
                        
                    ]),
                ],
            ])
             ->add('email')
            
            //  ->add('email', TextType::class, [
            //     'constraints' => [
            //         new Assert\NotBlank(['message' => 'L\'email est requis.']),
            //         new Assert\Email(['message' => 'L\'email n\'est pas valide.']),
            //         new UniqueEntity([
            //             'fields' => 'email',
            //             'message' => 'Cet email est déjà utilisé.',
            //         ]),
            //     ],
            // ])
          ->add('password', PasswordType::class, [
    'label' => 'Mot de passe',
    'mapped' => false, // Ne pas mapper ce champ à l'entité directement
    'constraints' => [
        new Assert\NotBlank(['message' => 'Le mot de passe est requis.']),
        // Ajoutez ici des contraintes de validation pour le mot de passe si nécessaire
    ],
]);


    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
