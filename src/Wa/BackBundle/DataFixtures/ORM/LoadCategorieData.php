<?php
namespace Wa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Wa\BackBundle\Entity\Categorie;


class LoadUserData implements FixtureInterface
{
    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $categorie = new Categorie();
        $categorie->setTitle('admin');
        $categorie->setDescription('test');
        $categorie->setPosition(2);
        $categorie->setActive(0);

        $manager->persist($categorie);
        $manager->flush();
    }
}