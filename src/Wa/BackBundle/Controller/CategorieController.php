<?php

namespace Wa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Wa\BackBundle\Entity\Categorie;
use Wa\BackBundle\Form\CategorieType;

class CategorieController extends Controller
{

    /*public function listAction()
    {
        $categories = [
            1 => [
                "id" => 1,
                "title" => "Homme",
                "description" => "lorem ipsum \n suite du contenu",
                "date_created" => new \DateTime('now'),
                "active" => true
            ],
            2 => [
                "id" => 2,
                "title" => "Femme",
                "description" => "<strong>lorem</strong> ipsum",
                "date_created" => new \DateTime('-10 Days'),
                "active" => true
            ],
            3 => [
                "id" => 3,
                "title" => "Enfant",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('-1 Days'),
                "active" => false
            ],
        ];

        return $this->render('WaBackBundle:Categorie:index.html.twig', array('categories' => $categories));

    }

    public function showAction($id)
    {
        $categories = [
            1 => [
                "id" => 1,
                "title" => "Homme",
                "description" => "lorem ipsum \n suite du contenu",
                "date_created" => new \DateTime('now'),
                "active" => true
            ],
            2 => [
                "id" => 2,
                "title" => "Femme",
                "description" => "<strong>lorem</strong> ipsum",
                "date_created" => new \DateTime('-10 Days'),
                "active" => true
            ],
            3 => [
                "id" => 3,
                "title" => "Enfant",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('-1 Days'),
                "active" => false
            ],
        ];

        if (array_key_exists($id, $categories)):
            $categorie = $categories[$id];
            //$categorie = array_keys($categories, $id);
        else:
            throw $this->createNotFoundException('La catégorie n\'existe pas');
        endif;

        return $this->render('WaBackBundle:Categorie:show.html.twig', array('categorie' => $categorie));

    }*/
    /**** LISTER LES CATEGORIES ***/
    public function indexAction()
    {
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité catégorie
        $categories= $em->getRepository("WaBackBundle:Categorie")
            -> findAll();//finby+champsEntité (méthode symfony)
        //die(dump($categories));
        return $this->render('WaBackBundle:Categorie:index.html.twig',["categories"=>$categories]);
    }

    /**** CREER UNE CATEGORIE ***/
    public function createAction(Request $request){
        $categories = new Categorie();

        $formCategorie= $this->createForm(new CategorieType(),$categories)
            ->add("Ajouter","submit");

        //Recupère tt ce que le user tape dans le form
        $formCategorie->handleRequest($request);

        if($formCategorie->isValid()){
            /*//die(dump($categories));
            $image= $categories->getImage();
            //Appel de la fonction de l'objet image
            $image->upload();*/

            //On récupère la cnx° (Doctrine)
            $em= $this->getDoctrine()->getManager();
            //Surveille l'objet et ses modifications
            //Je delete les 2 lignes en dessous car cascade persist dans l'entité categorie
            //$em->persist($image);
            //$em->flush();
            $em->persist($categories);
            $em->flush();
            //Msg flash
            $this->get("session")->getFlashBag()
                ->add("success","1 catégorie a été créé");

        }
        return $this->render('WaBackBundle:Categorie:formCategorie.html.twig', ["formulaireCategorie"=>$formCategorie->createView()]);
    }

    public function renderAllCategorieAction(){
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité catégorie
        $categories= $em->getRepository("WaBackBundle:Categorie")
            ->findAll();
        return $this->render("WaBackBundle:Categorie:renderCategorie.html.twig",["categories"=>$categories]);
    }

    public function showAction(Categorie $categorie){
        return $this->render('WaBackBundle:Categorie:show.html.twig', array('categorie' => $categorie));
    }

    /***EDITER CATEGORIE***/
    public function updateAction(Request $request, $id){
        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité produit
        $categorie= $em->getRepository("WaBackBundle:Categorie")
            ->find($id);

        //Creation formulaire
        $formCategorie = $this->createForm(new CategorieType(), $categorie)
        ->add("Modifier","submit");

        //dump($produit);
        //Recupère tt ce que le user tape dans le form
        $formCategorie->handleRequest($request);
        if($formCategorie->isValid()){
            //On récupère la cnx° (Doctrine)
            $em= $this->getDoctrine()->getManager();
            //Surveille l'objet et ses modifications
            $em->persist($categorie);
            $em->flush();
        }
        return $this->render('WaBackBundle:Categorie:formUpdateCategorie.html.twig', ["formulaireCategorie"=>$formCategorie->createView()]);
    }

    /***SUPPRIMER CATEGORIE ***/
    public function deleteAction($id, Request $request){

        //Repository endroit ou il va chercher les requêtes personnalisées
        $em= $this->getDoctrine()->getManager();
        //Va regarder dans l'entité catégorie
        $categorie= $em->getRepository("WaBackBundle:Categorie")
            ->find($id);
        if(!$categorie){throw $this->createNotFoundException("Catégorie inexistant !");}

        $em->remove($categorie);
        $em->flush();
        //Si la requete et en ajax renvoyer quelque chose (booléan au autre pour confirmer que ça s'est bien passé)
        if($request->isXmlHttpRequest()){return new JsonResponse();}

        return $this->redirectToRoute("wa_back_categorie");
    }
}
