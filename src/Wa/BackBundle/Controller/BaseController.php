<?php

namespace Wa\BackBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller{

    public function breadCrumbs($items){
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        // Simple example
        $breadcrumbs->addRouteItem("Dashboard", "wa_back_homepage");
        //Pour chaque url correspondant à un label d'un item
        foreach($items as $label => $url)
        {
            //Si c'est vide
            if (!empty($url))
            {
                //afficher dans le breadcrumb le label associé à l'url cliquable
                $breadcrumbs->addItem($label, $url);
            }
            //On gère les labels
            else
            {
                $breadcrumbs->addItem($label);
            }
        }
    }
}