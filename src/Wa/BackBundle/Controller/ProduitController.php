<?php

namespace Wa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wa\BackBundle\Entity\Produit;
use Wa\BackBundle\Form\ProduitType;

//use Symfony\Component\HttpFoundation\Response;

class ProduitController extends Controller
{

    public function showAction($id){
        // CODE POUR APPRENDRE
        /*
        $products = [
            [
                "id" => 1,
                "title" => "Mon premier produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 10
            ],
            [
                "id" => 2,
                "title" => "Mon deuxième produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 20
            ],
            [
                "id" => 3,
                "title" => "Mon troisième produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 30
            ],
            [
                "id" => 4,
                "title" => "",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 410
            ],
        ];

        if (array_key_exists($id, $products)):
                $produit = $products[$id];
        else:
            throw $this->createNotFoundException('Le produit n\'existe pas');
        endif;
        FIN CODE PR APPRENDRE*/
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité produit
        $produit= $em->getRepository("WaBackBundle:Produit")
                    ->find($id);
        return $this->render('WaBackBundle:Produit:show.html.twig', array('produit' => $produit));
    }

    public function indexAction()
    {
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité produit
        $produits= $em->getRepository("WaBackBundle:Produit")
                        -> findAll();//finby+champsEntité (méthode symfony)
        //die(dump($produits));
        return $this->render('WaBackBundle:Produit:index.html.twig',["produits"=>$produits]);
    }

    public function createAction(Request $request){
        $produits = new Produit();
        /*$formProduit = $this->createFormBuilder($produits)
                            ->add("title","text")
                            ->add("description","textarea")
                            ->add("price")
                            ->add("quantity")
                            ->add("envoyer","submit")
                            ->getForm();*/
        $formProduit= $this->createForm(new ProduitType(),$produits)
                            ->add("envoyer","submit");
        //dump($produit);
        //Recupère tt ce que le user tape dans le form
        $formProduit->handleRequest($request);
        if($formProduit->isValid()){
            //die(dump($produit));
            //On récupère la cnx° (Doctrine)
            $em= $this->getDoctrine()->getManager();
            //Surveille l'objet et ses modifications
            $em->persist($produits);
            $em->flush();
            //Msg flash
            $this->get("session")->getFlashBag()
                ->add("success","1 produit a été créé");
        }
        return $this->render('WaBackBundle:Produit:formProduit.html.twig', ["formulaireProduit"=>$formProduit->createView()]);
    }

    public function updateAction(Request $request, $id){
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité produit
        $produit= $em->getRepository("WaBackBundle:Produit")
            ->find($id);

        //Creation formulaire
        $formProduit = $this->createFormBuilder($produit)
            ->add("title","text")
            ->add("description","textarea")
            ->add("price")
            ->add("quantity")
            ->add("Modifier","submit")
            ->getForm();

        //dump($produit);
        //Recupère tt ce que le user tape dans le form
        $formProduit->handleRequest($request);
        if($formProduit->isValid()){
            //die(dump($produit));
            //On récupère la cnx° (Doctrine)
            $em= $this->getDoctrine()->getManager();
            //Surveille l'objet et ses modifications
            $em->persist($produit);
            $em->flush();
        }
        return $this->render('WaBackBundle:Produit:formUpdateProduit.html.twig', ["formulaireProduit"=>$formProduit->createView()]);
    }

    public function deleteAction($id){
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité produit
        $produit= $em->getRepository("WaBackBundle:Produit")
                ->find($id);
        if(!$produit){throw $this->createNotFound("Produit inexistant !");}

        $em->remove($produit);
        $em->flush();

        return $this->redirectToRoute("wa_back_produit");
    }

}
