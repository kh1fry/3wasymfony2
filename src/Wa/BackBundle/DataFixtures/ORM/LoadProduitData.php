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
        $faker = \Faker\Factory::create('fr_FR');

        // generate data by accessing properties
        /*
        echo $faker->name;
        die;
        */

        for($i=0; $i <10; $i++){
            //VOir la doc en tapant faker.php lien github (https://github.com/fzaninotto/Faker)
            $produit = new Produit();
            $produit->setTitle($faker->text(10));
            $produit->setPrice($faker->randomFloat(2,0,500));
            $produit->setQuantity($faker->numberBetween(0,1));
            $produit->setDescription($faker->text(10));
            $produit->setDateCreated($faker->dateTimeThisYear);
            // Je récupère toutes les catégories grâce à $this->getReference("categ")
            // J'utilise $faker->randomElement afin de prendre une catégorie au hasard dans toutes les catégories
            //$categorie=$faker->randomElement($this->getReference("categ"));
            $categorie=$this->getReference("categ");
            $produit->setCategorie($categorie);
            $produit->setMarque($manager->getRepository('WaBackBundle:Marque')->find(1));

            $manager->persist($produit);
            $manager->flush();
        }
    }

    public function getOrder(){
        return 2;
    }
}