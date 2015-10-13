<?php

namespace Wa\BackBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wa\BackBundle\Entity\CategorieRepository;

class ProduitType extends AbstractType
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
            ->add('price')
            //création d'un date picker
            ->add('dateCreated', "date",[
                "widget"=>"single_text",
                "format"=>"dd/MM/yyyy"
                ])
            ->add('quantity')
            ->add('categorie','entity',[
                    "class"=>"WaBackBundle:Categorie",
                    "choice_label"=>"title",
                    /*
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('cat')
                            ->orderBy('cat.position', 'ASC');
                    }
                    */
                    "query_builder"=> function(CategorieRepository $er){
                     return $er->buildCategorieOrderPosition();

                    }
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wa\BackBundle\Entity\Produit'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wa_backbundle_produit';
    }
}
