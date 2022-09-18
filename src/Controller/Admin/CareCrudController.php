<?php

namespace App\Controller\Admin;

use App\Entity\Care;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CareCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Care::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
