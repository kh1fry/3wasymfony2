<?php

namespace Wa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wa\BackBundle\Entity\Categorie;
use Wa\BackBundle\Entity\Commentaire;
use Wa\BackBundle\Form\CommentaireType;
use Wa\BackBundle\Repository\CategorieRepository;
use Wa\BackBundle\Entity\Produit;
use Wa\BackBundle\Form\ProduitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
//use Symfony\Component\HttpFoundation\Response;

class ProduitController extends Controller
{

    // Produit $product : equivalent à $em->getRepository("WaBackBundle:Produit")->find($id)
//    public function showAction(Produit $produit, Request $request){
    // je n'utilise plus le paramconvert Produit $produit mais je vais faire une requête moi
    /**
     * @param $id
     * @param Request $request
     * @return Response
     * @ParamConverter("produit", options={"repository_method" = "findProductWithCommments"})
     */
    public function showAction($id, Request $request, Produit $produit){

        //On récupère la cnx° (Doctrine)
        $em = $this->getDoctrine()->getManager();

        //$produit = $em->getRepository('WaBackBundle:Produit')->findProductWithCommments($id);


        //Creation formulaire
        $commentaires = new Commentaire();

        $formCommentaire= $this->createForm(new CommentaireType(),$commentaires)
            ->add("Ajouter","submit");

        //Recupère tt ce que le user tape dans le form
        $formCommentaire->handleRequest($request);

        if($formCommentaire->isValid()) {

            // Lier le commentaire au produit
            $commentaires->setProduit($produit);

            //Surveille l'objet et ses modifications
            //Je delete les 2 lignes en dessous car cascade persist dans l'entité categorie
            //$em->persist($image);
            //$em->flush();
            $em->persist($commentaires);
            $em->flush();
            //Msg flash
            $this->get("session")->getFlashBag()
                ->add("success", "1 commentaire a été ajouté");
        }

        //AFFICHER LES COMMENTAIRES PAR PRODUIT
//        $comByPdt= $em->getRepository("WaBackBundle:Commentaire")
//            ->commentByPdt(18);
        // je met à zéro ce tableau car il existe une propriété commentaires dans l'entité produit
        // on va donc utiliser cette propriété plutôt que la requête du dessus permettant de
        // récupérer les commentaires
        $comByPdt = [];

        return $this->render('WaBackBundle:Produit:show.html.twig',
            [
                'produit' => $produit,
                'formulaireCommentaire'=>$formCommentaire->createView(),
                'comByPdt'=>$comByPdt
            ]
        );
    }

    public function indexAction()
    {
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité produit
        $produits= $em->getRepository("WaBackBundle:Produit")
                        //-> findAll();//finby+champsEntité
                        ->findProduitByCategorie();
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
        return $this->render('WaBackBundle:Produit:formProduit.html.twig',
            [
                "formulaireProduit"=>$formProduit->createView()
            ]);
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

    public function deleteAction($id, Request $request){

        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité produit
        $produit= $em->getRepository("WaBackBundle:Produit")
                ->find($id);
        if(!$produit){throw $this->createNotFoundException("Produit inexistant !");}

        $em->remove($produit);
        $em->flush();
        //Si la requete et en ajax renvoyer quelque chose (booléan au autre pour confirmer que ça s'est bien passé)
        if($request->isXmlHttpRequest()){return new JsonResponse();}

        return $this->redirectToRoute("wa_back_produit");
    }

}
