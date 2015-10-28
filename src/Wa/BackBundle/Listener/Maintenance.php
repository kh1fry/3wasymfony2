<?php
namespace Wa\BackBundle\Listener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;


class Maintenance
{
    private $twig;

    private $maintenance;

    private $environnement;

    public function __construct($twig, $maintenance, $environnement)
    {
        $this->twig = $twig;
        $this->maintenance = $maintenance;
        $this->environnement = $environnement;
    }

    public function miseEnMaintenance(GetResponseEvent $event){

        //Si je suis en maintenance et qu'on est en environnement de prod

        if ($this->maintenance && $this->environnement == 'prod')
        {
            $contenuHTML = $this->twig->render('WaBackBundle:Partial:maintenance.html.twig');
            //Par défaut le code respone est 200 on le passe en 503 = maintenance
            $event->setResponse(new Response($contenuHTML, 503)); // contenu et code maintenance
            $event->stopPropagation();
        }


    }
}