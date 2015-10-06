<?php

namespace Wa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

//use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{

    public function adminAction()
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
        return $this->render('WaBackBundle:Main:admin.html.twig',
            array('categories' => $categories, 'products' => $products)
        );

    }

    public function contactAction(Request $request)
    {
        $formulaireContact= $this-> createFormBuilder()
                            //Ajout champs formulaire
                            ->add("firstname","text")
                            ->add("lastname","text")
                            ->add("email","email")
                            ->add("content","textarea")
                            ->add("send","submit")
                            //Récupérer le formulaire
                            ->getForm();
        //Test l'égalité et le type
        if("POST" === $request->getMethod()){
            //die(dump($request->request->all()));
            $formulaireContact->bind($request);

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

            }

            return $this->render('WaBackBundle:Main:feedback.html.twig',["formulaireFeedback"=>$formulaireFeedback->createView()]);

    }




}
