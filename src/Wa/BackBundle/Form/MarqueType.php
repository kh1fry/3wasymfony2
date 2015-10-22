<?php

namespace Wa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wa\BackBundle\Entity\Tag;
use Wa\BackBundle\Form\DataTransformer\TagTransformer;
use Wa\BackBundle\Repository\TagRepository;

class MarqueType extends AbstractType
{

    private $em;

    public function __construct($em = null)
    {
        $this->em = $em;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //die(dump($this->em));
        $builder
            ->add('titre');
           // ->add('slug')
            /*
            ->add('tags', 'entity',[
                "class"=>"WaBackBundle:Tag",
                "multiple"=>true,
                "choice_label"=>"nom",
                "query_builder"=> function(TagRepository $er){
                    return $er->findAllTagsOrder();
                }
                ]);
            */
            /*->add('tags', 'collection', array(
                'type' =>new TagWithoutMarqueType(),
                //On veut en ajouter plusieurs
                'allow_add'=>true
            ));*/

        $builder->add(
            $builder->create('tags','collection',array(
                'type'=>new TagWithoutMarqueType(),
                'allow_add' =>true

            ))->addModelTransformer(new TagTransformer($this->em))
        );

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wa\BackBundle\Entity\Marque'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wa_backbundle_marque';
    }
}
