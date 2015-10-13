<?php

namespace Wa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        $productAll= $em->getRepository("WaBackBundle:Produit")
                        // equivalent du ->findAll()
                        ->findAllPerso();
        //dump($productAll);
        //die();

        $product= $em->getRepository("WaBackBundle:Produit")
                    // equivalent de ->find(id)
                    ->findPerso(14);

        return $this->render('WaBackBundle:Main:admin.html.twig',
            [
                'productAll' => $productAll,
                'productDetail' => $product,
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
