<?php

namespace Wa\BackBundle\Util;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints as Assert;
use Wa\BackBundle\Entity\Produit;

class Panier
{
    private $em;
    private $session;

    public function __construct(EntityManager $em, Session $session)
    {
        $this->em = $em;
        $this->session = $session;
    }


    public function add(Produit $produit, $qty = 1){
        //Soit j'ai un tableau vide soit un tableau plein d'id produit
        if ($this->session->get('panier')) {
            $allProducts = $this->session->get('panier');
        } else {
            $allProducts = [];
        }
        //TRaitement sur la quantité
        if (array_key_exists($produit->getId(), $allProducts)){
            // $allProducts[$product->getId()]; représente la quantité du produit
            $allProducts[$produit->getId()] = $allProducts[$produit->getId()] + $qty;
            //$qty = $allProducts[$product->getId()] + $qty
        }else{
            $allProducts[$produit->getId()] = $qty;
        }
        $this->session->set('panier', $allProducts);
    }

    public function displayPanier(){
        //Déclaration d'un tableau de produit du panier qu'on utilisera pour afficher les produits
        //du panier dans la vue
        $pdtPanier=[];

        //Variable pour récupérer le montant total du panier
        $total=0;

        //Pour chaque produit du panier
        if ($this->session->get('panier')) {
            $allProducts = $this->session->get('panier');
        } else {
            $allProducts = [];
        }



        $pdtPanier['total'] = 0;

        foreach ($allProducts as $idProduct => $qty){

            //Récupérer l'objet produit
            $produit= $this->em->getRepository('WaBackBundle:Produit')->find($idProduct);
            $produit->qtyPanier = $allProducts[$idProduct];

            //Calculer le montant total du produit
            $totalProduit= ($produit->getPrice() * $produit->qtyPanier);

            //Ajouter le produit au tableau
            $pdtPanier['pdtPanier'][] = $produit;

            // Ajoute le total de tous les produits (total produit en cours plus ceux d'avant)
            $pdtPanier['total'] += $totalProduit;

        }

        //Retourner les éléments à afficher
        return $pdtPanier;
    }

    public function removePanier(){

    }
}