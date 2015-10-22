<?php

namespace Wa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Wa\BackBundle\Entity\Tag;
use Wa\BackBundle\Form\TagType;


class TagController extends Controller
{
    /**** LISTER LES TAGS ***/
    /*public function indexAction()
    {
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité catégorie
        $tags= $em->getRepository("WaBackBundle:Tag")
            -> findAll();//finby+champsEntité (méthode symfony)
        //die(dump($tags));
        return $this->render('WaBackBundle:Partial:sidebar.html.twig',["tags"=>$tags]);
    }*/

    /**** AFFICHER LES TAGS DANS LA SIDEBAR ***/
    public function renderAllTagsAction(){
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité catégorie
        $tags= $em->getRepository("WaBackBundle:Tag")
            ->findAll();
        return $this->render("WaBackBundle:Tag:renderTag.html.twig",["tags"=>$tags]);
    }

    /**** CREER UN TAG ***/
    public function newAction(Request $request){
        $tag = new Tag();

        $formTag= $this->createForm(new TagType(),$tag)
            ->add("Ajouter","submit");

        //Recupère tt ce que le user tape dans le form
        $formTag->handleRequest($request);

        if($formTag->isValid()){
            //On récupère la cnx° (Doctrine)
            $em= $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();
            //Msg flash
            $this->get("session")->getFlashBag()
                ->add("success","1 tag a été créé");

        }
        return $this->render('WaBackBundle:Tag:formTag.html.twig', ["formTag"=>$formTag->createView()]);
    }

    /**** AFFICHER UN TAG ET LES MARQUES LIEES ***/
    public function showAction($id){
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité catégorie
        $tag= $em->getRepository("WaBackBundle:Tag")
            -> findTagAndMarques($id);
        //die(dump($tags));
        return $this->render('WaBackBundle:Tag:show.html.twig',["tag"=>$tag]);
    }

}
