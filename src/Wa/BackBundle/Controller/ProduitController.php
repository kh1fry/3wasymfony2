<?php

namespace Wa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Wa\BackBundle\Entity\Produit;

//use Symfony\Component\HttpFoundation\Response;

class ProduitController extends Controller
{
    public function showAction($id){

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
        return $this->render('WaBackBundle:Produit:show.html.twig', array('produit' => $produit));
    }

    public function listAction(){
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

        return $this->render('WaBackBundle:Produit:list.html.twig', array('products' => $products));
    }

    public function createAction(Request $request){
        $produit = new Produit();
        //$produit->setTitle('Hello');
        $formProduit = $this->createFormBuilder($produit)
                            ->add("title","text")
                            ->add("description","textarea")
                            ->add("price")
                            ->add("quantity")
                            ->add("envoyer","submit")
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
        return $this->render('WaBackBundle:Produit:formProduit.html.twig', ["formulaireProduit"=>$formProduit->createView()]);
    }
}
