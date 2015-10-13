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
    public function load(ObjectManager $manager)
    {
        $categorie = new Categorie();
        $categorie->setTitle('admin');
        $categorie->setDescription('test');
        $categorie->setPosition(2);
        $categorie->setActive(0);

        $manager->persist($categorie);
        $manager->flush();
        $this->addReference("categ",$categorie);
    }
    public function getOrder(){
        return 1;
    }
}