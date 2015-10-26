<?php
namespace Wa\BackBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('mycommande:test')
            ->setDescription('permet de faire une commande de test')
            ->addArgument('prenom', InputArgument::OPTIONAL, 'veuillez entrer votre prénom')
            ->addOption('color','c',InputOption::VALUE_NONE, 'permet de mettre de la couleur');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Récupération de doctrine
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Récupération d'un argument
        $qte = $input->getArgument('quantite');
        if(!qte){
            $qte=5;
        }

        $produits= $em->getRepository('WaBackBundle:Produit')->findProduitByQte($qte);
        // Récupération d'une option
        $optionColor = $input->getOption('color');

        $output->writeln(count($produits).'produits ont une quantité inférieur à'.$qte);
    }

}
