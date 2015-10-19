<?php

namespace Wa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class CommentaireController extends Controller
{
    /**** LISTER LES COMMENTAIRES ***/
    public function indexAction()
    {
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité catégorie
        $commentaires= $em->getRepository("WaBackBundle:Commentaire")
            -> findAll();//finby+champsEntité (méthode symfony)
        //die(dump($categories));
        return $this->render('WaBackBundle:Commentaire:index.html.twig',["commentaires"=>$commentaires]);
    }

    /**** ACTIVER/DESACTIVER LE COMMENTAIRES ***/
    public function editAction($id){

        //Récupérer l'entity manager
        $em= $this->getDoctrine()->getManager();
        $commentaire= $em->getRepository('WaBackBundle:Commentaire')->find($id);


        // Si le commentaire est inactif
        if($commentaire->getActive() == 0) {
            // je passe par le setter pour le mettre inactif
            $commentaire->setActive(1);
        }else{
                $commentaire->setActive(0);
            }
            //Surveille l'objet et ses modifications
            $em->persist($commentaire);
            $em->flush();


        // je redirige sur la page de tous les commentaires
        return $this->redirectToRoute("commentaires");

    }

    public function isInterdit(){

    }
}
