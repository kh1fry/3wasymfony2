<?php

namespace Wa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * permet d'utiliser cette contraintes en annotation
 * @Annotation
 */
class Mdp extends Constraint
{
    public $message = "Ce password ne peut pas être utilisé";
    public $min= 6;
    public $message2 = "Le mot de passe doit faire au moins 6 caractères";
}