<?php

namespace Wa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * permet d'utiliser cette contraintes en annotation
 * @Annotation
 */
class AntiGrosMots extends Constraint
{
    public $message = "Un gros mot a été trouvé";
}