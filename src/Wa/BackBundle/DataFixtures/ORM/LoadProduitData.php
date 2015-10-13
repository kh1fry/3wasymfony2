<?php
namespace Wa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Wa\BackBundle\Entity\Produit;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class LoadProduitData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager){

        $categorie= $this->getReference("categ");

        $produit = new Produit();
        $produit->setTitle('Iphone');
        $produit->setPrice(10);
        $produit->setQuantity(1);
        $produit->setDescription('4S 32GO');
        $produit->setDateCreated(new \DateTime('now'));
        $produit->setCategorie($categorie);

        $manager->persist($produit);
        $manager->flush();
    }

    public function getOrder(){
        return 2;
    }
}