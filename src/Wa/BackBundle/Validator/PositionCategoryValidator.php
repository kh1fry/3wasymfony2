<?php

namespace Wa\BackBundle\Validator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PositionCategoryValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $position = $this->em->getRepository('WaBackBundle:Categorie')->findOneByPosition($value);
        //Si la position existe
        if ($position)
        {
            //On affiche le msg d'erreur
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}