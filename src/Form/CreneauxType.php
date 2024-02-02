<?php
// src/Form/CreneauxType.php

// src/Form/CreneauxType.php

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
            ->add('date', null, [
                'label' => 'Date:',
            
            ])
            ->add('permis', EntityType::class, [
                'class' => Permis::class,
                'label' => 'Type:',
                'choice_label' => 'type',
               
            ])
        
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Moniteur:',
                'choice_label' => 'firstname',
                'choices' => $options["moniteur"],
                
               
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
