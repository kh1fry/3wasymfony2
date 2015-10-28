<?php

namespace Wa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * permet d'utiliser cette contraintes en annotation
 * @Annotation
 */
class PositionCategory extends Constraint
{
    public $message = "La position existe déjà";


    public function validatedBy()
    {
        //Fait le lien avec l'alias dans le service
        return 'wa_back_position_category';
    }
}