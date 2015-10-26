<?php

namespace Wa\BackBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Wa\BackBundle\Entity\Produit;
use Wa\BackBundle\Util\Panier;


class PanierController extends Controller
{
    /**** AJOUTER AU PANIER ***/
    public function addAction(Request $request, Produit $produit)
    {
       /**** Sans la session
        //Récupérer la session
        $session= $request->getSession();
        //Supprimer la session
        //$session->remove('panier');
        //Déclarer un tableau de produits
        $allProducts=[];

        //La 1ere fois get('panier') n'existe pas
        if ($session->get('panier')){
            $allProducts= $session->get('panier');
            //die(dump('hello',$allProducts));
        }

        //Ajoute un produit au panier
        array_push($allProducts,$id);
        //die(dump($allProducts, json_encode($allProducts)));

        $session->set('panier',$allProducts);
        //die(dump('panier',$session->get('panier')));
        *****FIN****/

        /*** Avec la session ***/
        $panier = $this->get('wa_back.panier');
        //die(dump($panier));
        $panier->add($produit);

        return $this->redirectToRoute('wa_back_panier_display');
    }

    /**** LISTER LES PRODUITS DU PANIER ***/
    public function indexAction(Request $request )
    {
        /*//Récupérer l'entity manager
        $em= $this->getDoctrine()->getManager();

        //Récupérer la session
        $session= $request->getSession();

        //Déclaration d'un tableau de produit du panier qu'on utilisera pour afficher les produits
        //du panier dans la vue
        $pdtPanier=[];

        //Variable pour récupérer le montant total du panier
        $total=0;

        //Pour chaque produit du panier
        foreach ($session->get('panier') as $idProduct => $qty){

            //Récupérer l'objet produit
            $produit= $em->getRepository('WaBackBundle:Produit')->find($idProduct);

            //Calculer le montant total
            $total= $total + $produit->getPrice();
            //Ajouter dans le produit au tableau
            array_push($pdtPanier,$produit);

        }*/
        /*** Avec la session ***/
        $panier = $this->get('wa_back.panier');
        //die(dump($panier));
        return $this->render('WaBackBundle:Panier:index.html.twig',
                            ["infoPanier"=>$panier->displayPanier(),
                            ]);
    }

    public function deleteAction($id, Request $request){

        //Récupérer la session
        $session= $request->getSession();
        $cart = $session->get('panier');
        //Récupérer le produit à supprimer en fonction de la valeur
        $deletePdt= array_search($id,$session->get('panier'));
        //die(dump($deletePdt));
        //Supprimer l'élément dans le tableau du panier de produit
        unset($cart[$deletePdt]);

        $session->set('panier',$cart);

        return $this->redirectToRoute('wa_back_panier_display');
    }

    public function renderPdtPanierAction(Request $request){
        //Récupérer la session
        $session= $request->getSession();

        //Récupérer le nombre d'articles dans le panier
        $nbrPdt= count($session->get('panier'));

        return $this->render("WaBackBundle:Panier:renderPanier.html.twig",["nbrPdt"=>$nbrPdt]);
    }
}
