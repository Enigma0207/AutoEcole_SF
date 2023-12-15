<?php

namespace App\Form;

use App\Entity\Creneaux;
use App\Entity\Permis;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreneauxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date') // Ajoutez le champ date
            ->add('permis', EntityType::class, [
                'class' => Permis::class,
                'label' => 'Type',
                'choice_label' => 'type',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Élève',
                'choice_label' => 'firstname',
                'choices' => $options["eleve"],
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Moniteur',
                'choice_label' => 'firstname',
                'choices' => $options["moniteur"],
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Creneaux::class,
            'moniteur' => null,
            'eleve' => null,
        ]);
    }
}
