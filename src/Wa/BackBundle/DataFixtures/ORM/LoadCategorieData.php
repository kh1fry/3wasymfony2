<?php
namespace Wa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Wa\BackBundle\Entity\Categorie;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class LoadCategorieData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
    * {@inheritDoc}
    */
    /*public function load(ObjectManager $manager)
    {
        $categorie = new Categorie();
        $categorie->setTitle('admin');
        $categorie->setDescription('test');
        $categorie->setPosition(2);
        $categorie->setActive(0);

        $manager->persist($categorie);
        $manager->flush();
        $this->addReference("categ",$categorie);
    }*/
    public function load(ObjectManager $manager)
    {
        // Création du tableau permettant de stocker toutes les catégories
        //$allCategories = [];
        $faker = \Faker\Factory::create('fr_FR');

        // generate data by accessing properties
        /*echo $faker->name;
        die;*/

        for ($i=0; $i <10; $i++ ){
            $categorie = new Categorie();
            $categorie->setTitle($faker->text(10));
            //Si on ne précise pas la longueur il met 200 par défaut
            $categorie->setDescription($faker->text);
            $categorie->setPosition($faker->randomDigitNotNull);
            $categorie->setActive($faker->numberBetween(0,1));

            $manager->persist($categorie);
            $manager->flush();
            //array_push($allCategories, $categorie);
        }
        // J'envoie toutes les catégories afin de les récupérer dans les fixtures des produits
        $this->addReference('categ', $categorie);
    }

    public function getOrder(){
        return 1;
    }
}