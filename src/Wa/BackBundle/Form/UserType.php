<?php

namespace Wa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wa\BackBundle\Form\Type\TelType;
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
            ->add('gender', 'gender')
            ->add('address')
            ->add('phone', new TelType())
            ->add('groupes','entity',[
                "by_reference" => false,
                "class"=>"WaBackBundle:Groupe",
                "multiple"=>true,
                "expanded" => true,
                "choice_label"=>"name",
                "query_builder"=> function(GroupeRepository $er){
                    return $er->listGroupeOrderName();
                }]);

        //Je lance un événement avant que le formulaire ne s'affiche
        // Greffer un événement PRE_SET_DATA (avant l'affichage du formulaire)*
        // On lance la méthode editUser
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'editUser']);
    }

    //Permet de récupérer le form et les infos sur l'entité user'
    public function editUser(FormEvent $event)
    {
        //die('ok');

        //Récupère l'objet du form en cours
        $user = $event->getData();
        //Récupère le formulaire
        $form = $event->getForm();


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
