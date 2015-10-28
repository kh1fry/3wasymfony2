<?php

namespace Wa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('position')
            ->add('active')
            //Création du form Image à l'intérieur du form Cat
            ->add('image',new ImageType())
        ;
        //Je lance un événement avant que le formulaire ne s'affiche
        // Greffer un événement PRE_SET_DATA (avant l'affichage du formulaire)*
        // On lance la méthode editUser
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'editCat']);
    }
    //Permet de récupérer le form et les infos sur l'entité user'
    public function editCat(FormEvent $event)
    {
        //die('ok');

        //Récupère l'objet du form en cours
        $categorie = $event->getData();
        //Récupère le formulaire
        $form = $event->getForm();


        //die(dump($categorie, $form));
        //Si j'ai au moins un utilisateur en bdd et que l'id existe == c'est une édition
        if($categorie && $categorie->getId()){
            //On remove le champ login
            $form->remove('position');
        }

    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wa\BackBundle\Entity\Categorie'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wa_backbundle_categorie';
    }
}
