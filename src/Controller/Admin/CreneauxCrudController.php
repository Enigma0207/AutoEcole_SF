<?php

namespace App\Controller\Admin;

use App\Entity\Creneaux;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField; // Ajoutez cet import
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField; // Ajoutez cet import
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField; // Ajoutez cet import


class CreneauxCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Creneaux::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('date'),
            BooleanField::new('isAvailable'),
            AssociationField::new('user'),
            AssociationField::new('permis'),
        ];
    }
    
}
