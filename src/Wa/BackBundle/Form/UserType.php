<?php

namespace Wa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wa\BackBundle\Repository\GroupeRepository;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email', 'email')
            ->add('login')
            ->add('password')
            ->add('gender')
            ->add('address')
            ->add('phone')
            ->add('groupes','entity',[
                "by_reference" => false,
                "class"=>"WaBackBundle:Groupe",
                "multiple"=>true,
                "choice_label"=>"name",
                "query_builder"=> function(GroupeRepository $er){
                    return $er->listGroupeOrderName();
                }]);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wa\BackBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wa_backbundle_user';
    }
}
