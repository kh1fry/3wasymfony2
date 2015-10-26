<?php
namespace Wa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Wa\BackBundle\Entity\Produit;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Wa\BackBundle\Entity\User;


class LoadProduitData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager){
        $faker = \Faker\Factory::create('fr_FR');

        for($i=0; $i <10; $i++){
            //Voir la doc en tapant faker.php lien github (https://github.com/fzaninotto/Faker)
            $user = new User();
            $user->setFirstname($faker->text(10));
            $user->setLastname($faker->text(10));
            $user->setEmail($faker->text(10));
            $user->setLogin($faker->text(10));
            $user->setPassword($faker->text(10));
            $user->setGender($faker->text(10));
            $user->setAddress($faker->text(10));
            $user->setPhone($faker->text(10));

            $manager->persist($user);
            $manager->flush();
        }
    }

    public function getOrder(){
        return 3;
    }
}