<?php

namespace Wa\BackBundle\Controller;

use MetzWeb\Instagram\Instagram;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class MainController extends Controller
{

    public function adminAction()
    {
        //Doctrine pour gérer des bbd !=
        //Manager pour gérer des bundles !=
        $em= $this->getDoctrine()
                ->getManager();

        //AFFICHER TOUT LES PRODUITS
        $productAll= $em->getRepository("WaBackBundle:Produit")
                        // equivalent du ->findAll()
                        ->findAllPerso();

        //AFFICHER UN PRODUIT
        $product= $em->getRepository("WaBackBundle:Produit")
                    // equivalent de ->find(id)
                    ->findPerso(14);

        //AFFICHER LES PRODUITS PAR CATEGORIE
        $productCatHome=$em->getRepository("WaBackBundle:Produit")
            ->pdtWithCatHome(1);

        //AFFICHER LES
        $pdtSansCat= $em->getRepository("WaBackBundle:Produit")
            ->pdtSansCategorie();

        //AFFICHER LE NBR DE PRODUITS PAR CATEGORIE
        $nbrPdtByCat= $em->getRepository("WaBackBundle:Produit")
            ->nbrPdtByCat();

        //AFFICHER LE PRODUIT LE PLUS CHER
        $pdtPrixMax= $em->getRepository("WaBackBundle:Produit")
            ->pdtPrixMax();

        //AFFICHER LES CATEGORIES SANS IMAGES
        $catSansImg= $em->getRepository("WaBackBundle:Categorie")
            ->catSansImg();

        //UTILISER L'APPLI INSTAGRAM
        // Chemin ou on crée le fichier
        $file = __DIR__."/../../../../app/cache/cache_instagram.txt";
        $fs = new Filesystem();
        //Timestamp du cache
        $timeCache = time() - ( 1 * 60 );

        //On vérifie qu'on a fait un cache d'1 min avec un dump
        //dump(date ("F d Y H:i:s.", filemtime($file)));
        //dump(date ("F d Y H:i:s.", $timeCache));
        //die(dump($timeCache, filemtime($file)));

        //Si le fichier existe et que le temps d'utilisation de la dernière modif du fichier > timestamp
        //prévu dans le cache
        clearstatcache();
        if ($fs->exists($file) && (filemtime($file) > $timeCache)){
            //On récupère le contenu du fichier cacheinsta
            $mesImages = unserialize(file_get_contents($file));
            //die('cache');
        }
        else {
            //die(dump($this->getParameter('client_id_instagram')));
            $instagram = new Instagram(array(
                'apiKey' => $this->getParameter('client_id_instagram'),
                'apiSecret' => $this->getParameter('client_secret_instagram'),
                'apiCallback' => $this->getParameter('callback_instagram')
            ));

            //Récupérer le token après le code=
            //die(dump($instagram->getLoginUrl()));
            $instagram->setAccessToken($this->getParameter('token_instagram'));
            //die(dump($instagram->getPopularMedia()));

            $mesImages = $instagram->getUserMedia($this->getParameter('id_instagram'));


            //Mettre les infos dans le fichier
            //Création du fichier
            $fs->touch($file);
            //Mettre les images dans le fichier
            $fs->dumpFile($file, serialize($mesImages));
            //dump(file_get_contents($file));
            //dump($mesImages);
            //die('Utilisation du cache');
        }

        //die(dump($mesImages));
        //Parcourir l'objet et afficher ce qu'on veut
        /*foreach($instagram->getPopularMedia()->data as $media)
        {
            //die(dump($media));
            echo "<img src='".$media->images->thumbnail->url."'>";
            //die;
        }*/

        return $this->render('WaBackBundle:Main:admin.html.twig',
            [
                'productAll' => $productAll,
                'productDetail' => $product,
                'productAccueil'=> $productCatHome,
                'productSansCat'=> $pdtSansCat,
                'nbrPdtByCat'=>$nbrPdtByCat,
                'pdtPrixMax'=>$pdtPrixMax,
                'catSansimage'=>$catSansImg,
                'instagram'=>$mesImages
            ]);
    }

    public function contactAction(Request $request)
    {
        $formulaireContact= $this-> createFormBuilder()
                            //Ajout champ ("nom champs","type de champs)
                            ->add("firstname","text", ["constraints"=>
                                        //Si rien dans firstname => error
                                        [new Assert\NotBlank()],"required"=>false])
                            ->add("lastname","text", ["constraints"=>new Assert\NotBlank()])
                            ->add("email","email",
                                [
                                    "constraints"=>
                                    [
                                        new Assert\NotBlank((["message"=>"Entrer un email"])),
                                        new Assert\Email(
                                            [
                                                "message"=>"Email invalide",
                                                "checkMX"=> true,
                                            ]
                                        )
                                    ]
                                ])
                            ->add("content","textarea")
                            ->add("send","submit")
                            //Récupérer le formulaire
                            ->getForm();
        //Test l'égalité et le type
        $formulaireContact-> handleRequest($request);
            //Vérifie si le conditions form sont valide
            //ainsi que faille csrf pr voir si le token est bien le nôtre
            //et que c'est la bonne méthode 'POST'
            if($formulaireContact->isValid()) {

                $data = $formulaireContact->getData();

                $message = \Swift_Message::newInstance()
                    ->setSubject('Hello Email')
                    ->setFrom('yk.mukenge@gmail.com')
                    ->setTo('yk.mukenge@gmail.com')
                    ->setBody(
                        $this->renderView(
                            'WaBackBundle:Emails:contact.html.twig',
                            ['data' => $data]
                        ),
                        'text/html'
                    )

                ;
                $this->get('mailer')->send($message);
                $this->get("session")->getFlashBag()
                    ->add("success_contact","Le mail a bien été envoyé");

                return  $this->redirectToRoute("wa_back_contact");
            }
        return $this->render('WaBackBundle:Main:contact.html.twig',["formulaireContact"=>$formulaireContact->createView(), "prenom" => "yannick"]);

    }

    public function aboutAction()
    {
        return $this->render('WaBackBundle:Main:about.html.twig');

    }

    public function feedbackAction(Request $request)
    {
        $formulaireFeedback= $this-> createFormBuilder()
            //Ajout champs formulaire
            ->add("page","url")
            ->add("bugstatus",'choice',array(
                'choices' => array('1' => 'Bon', '2' => 'Moyen', '3' => 'Mauvais'),
            ))
            ->add("email","email")
            ->add("date","date", [
                "years" => range(date('y')-1,date('y'))
            ])
            ->add("send","submit")
            //Récupérer le formulaire
            ->getForm();

            if("POST" === $request->getMethod()) {
                $formulaireFeedback->bind($request);
                if($formulaireFeedback->isValid()) {
                    $data = $formulaireFeedback->getData();

                    $message = \Swift_Message::newInstance()
                        ->setSubject('Hello Email')
                        ->setFrom('yk.mukenge@gmail.com')
                        ->setTo('yk.mukenge@gmail.com')
                        ->setBody(
                            $this->renderView(
                                'WaBackBundle:Emails:contact.html.twig',
                                ['data' => $data]
                            ),
                            'text/html'
                        )

                    ;
                    $this->get('mailer')->send($message);
                    $this->get("session")->getFlashBag()
                        ->add("success_contact","Le mail a bien été envoyé");

                    return  $this->redirectToRoute("wa_back_contact");
                }

            }

            return $this->render('WaBackBundle:Main:feedback.html.twig',["formulaireFeedback"=>$formulaireFeedback->createView()]);

    }




}
