<?php
namespace Wa\BackBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenderType extends AbstractType
{
    private $genders;

    public function __construct($genders)
    {
        $this->genders = $genders;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        //die(dump($this->genders));
        $resolver->setDefaults(
            [
                "choices" => $this->genders

            ]);
    }

    public function getName()
    {
        return 'gender';
    }

    public function getParent()
    {
        return 'choice';
    }
}