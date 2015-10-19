<?php

namespace Wa\BackBundle\Repository;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllPerso(){
        //die("ok")
        //SELECT * == SELECT prod
        /*
        $query= $this->getEntityManager()
                    ->createQuery("
                SELECT prod
                FROM WaBackBundle:Produit prod
        ");
        */
        $query= $this->createQueryBuilder("prod")
                    ->getQuery();

       // die(dump($query->getResult()));
        return $query->getResult();
    }

    public function findPerso($id)
    {

        $query= $this->createQueryBuilder("prod")
            ->where('prod.id = :idProd')
            ->setParameter("idProd", $id)
            ->getQuery();

        //die(dump($query->getSingleResult()));



        /*Dans la clause WHERE idProd est une variable qui va contenir la valeur
         parametre id*/

        // _em = getEntityManager()
        /*$query= $this->_em->createQuery("
            SELECT prod
            FROM WaBackBundle:Produit prod

            WHERE prod.id= :idProd
        ")
        ->setParameter("idProd", $id);
        /*
         * permet d'avoir plusieurs arguments
        ->setParameters(
            [
                "idProd" => $id,
            ]
        );
        */

        return $query->getSingleResult();
    }

    //AFFICHER PRODUIT AVEC QTE INFERIEUR A PARAMETRE
    public function listeProduitQte($quantite){
        $query= $this->createQueryBuilder("prod")
                    ->where('prod.quantite < qteProd')
                    ->setParameter("qteProd",$quantite)
                    ->getQuery();
        //die(dump($query->getResult()));

        return $query->getResult();
    }

    //AFFICHER LE PRIX MAX ET MIN
    public function prixMaxMin(){
    }

    public function findProduitByCategorie(){
        $query= $this->getEntityManager()
                    ->createQuery("
                        SELECT prod, cat
                        FROM WaBackBundle:Produit prod
                        LEFT JOIN prod.categorie cat
                    ");

        return $query->getResult();
    }

    public function findProduitByQuantite($qte){
        $query= $this->createQueryBuilder("prod")
            ->where('prod.quantite < :qteProd')
            ->setParameter("qteProd",$qte)
            ->getQuery();

        return $query->getResult();
    }


    //AFFICHER PRODUITS AVEC CATEGORIE == ACCUEIL (NON Opimisées la jointure est en trop)
    public function pdtWithCatHome($id){
        /*
         Cela créer le select et le from de l'entité du repository (ici product)
         $this->createQueryBuilder("prod")

        Cela créer un createQueryBuilder vide (if faut faire le select et le from)
        $this->getEntityManager()->createQueryBuilder()->select()->from()
         */
        $query= $this->createQueryBuilder("prod")
            ->join('prod.categorie', 'cat')
            ->where('cat.id = :idValue')
            //->setParameter('idValue', $id)
            ->setParameters(
                [
                    'idValue' => $id
                ]
            )
            ->getQuery();


        //die(dump($query->getResult()));
        return $query->getResult();
    }

    //AFFICHER PRODUIT PAR CATEGORIE
    public function pdByCategorie($value){
        $query= $this->createQueryBuilder("prod")
                ->where('prod.categorie = :value')
                ->setParameter('value', $value)
                ->getQuery();

        die(dump($query->getResult()));
        return $query->getResult();
    }

    //AFFICHER PRODUIT SANS CATEGORIE
    public function pdtSansCategorie(){
        $query= $this->createQueryBuilder("prod")
            ->where('prod.categorie IS NULL')
            ->getQuery();

        //die(dump($query->getResult()));
        return $query->getResult();
    }

    //AFFICHCER NBR PDT PAR CATEGORIE
    public function nbrPdtByCat(){
        $query= $this->createQueryBuilder("prod")
            ->select('COUNT(prod.id) as nb,cat.title')
            ->join('prod.categorie', 'cat')
            ->groupBy('prod.categorie')
            ->getQuery();

        return $query->getResult();
    }

    //AFFICHER LE PRODUIT LE PLUS CHER
    public function pdtPrixMax(){
        $query= $this->createQueryBuilder("prod")
            ->select('MAX(prod.price)')
            ->getQuery();

        //die(dump($query->getResult()));
        return $query->getResult();
    }



}

