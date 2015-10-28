<?php

namespace Wa\BackBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Wa\BackBundle\Entity\User;
use Wa\BackBundle\Form\UserType;

class UserController extends Controller{

    public function newAction(Request $request){
        //On crée un objet user
        $user= new User();

        //Création du formulaire
        $formUser= $this->createForm(new UserType(), $user)
                    //Gérer les conditions générales de vente
                    ->add('agree','checkbox', [
                        "label" => "I agree",
                        "constraints" => new NotBlank(),
                        "mapped" => false //Permet d'ajouter un champ non mapé
                    ])
                    ->add("creer", "submit");

        //On récupère les data entrer dans le formulaire
        $formUser->handleRequest($request);
        if($formUser->isValid()){
            //On récupère tout ce quil y a dans l'encoder au niveau du fichier yml
            $factory = $this->get('security.encoder_factory');
            // Je récupère l'encoder de la class Troiswa\BackBundle\Entity\User
            $encoder = $factory->getEncoder($user);
            //On encode le mdp (pour infos on aurait pu récupérer les infos du form
            //avec $user->password
            $newPassword = $encoder->encodePassword($formUser->get('password')->getData(), $user->getSalt());

            //On set le mdp crypté
            $user->setPassword($newPassword);

            //On récupère l'E.M.
            $em= $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            //Msg flash
            $this->get("session")->getFlashBag()
                ->add("success", "1 utilisateur a été créé");
        }
        return $this->render('WaBackBundle:User:new.html.twig',
            [
                "formUser"=>$formUser->createView()
            ]);
    }

}