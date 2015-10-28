<?php

namespace Wa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Wa\BackBundle\Validator\PositionCategory;
/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="Wa\BackBundle\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="smallint")
     * @PositionCategory()
     */
    private $position;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var \Integer
     *
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $image;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Categorie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Categorie
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Categorie
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Categorie
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @Assert\Callback
     */
    public function titleMajValidate(ExecutionContextInterface $context)
    {
        if( ucfirst($this->title) != $this->title) {
            $context->buildViolation('Le titre doit avoir la 1ère lettre en majuscule')
                ->atPath('title')
                ->addViolation();
        }

    }

    /*TEST DE LA POSITION*/
    /* Contrainte pour toute l'entité et donc visible pour TOUT le formulaire (aucun champ ciblé en particulier) */
        /**
     * @Assert\True(message="Si la position est 1 alors il faut que le title soit Accueil")
     */
    public function isCategorieValide()
    {
        if (($this->position == 1)&&($this->position != "Accueil")){
            return false;
        }
    }

    /**
     * @Assert\Callback
     */
    public function isInterdit(ExecutionContextInterface $context)
    {
        if (preg_match("/\b(".$this->title.")\b/i",$this->description)){
            $context->buildViolation('Le titre ne doit pas être compris dans la description')
                ->atPath('title')
                ->addViolation();
        }
    }

    /*public function __toString()
    {
        return $this->title;

    }*/

    /**
     * Set image
     *
     * @param \Wa\BackBundle\Entity\Image $image
     *
     * @return Categorie
     */
    public function setImage(\Wa\BackBundle\Entity\Image $image = null)
    {
        //Permettre d'avoir une catégorie sans img
        if($image == null|| !$image->getFile()){
            $image= null;
        }
        //die(dump($image));
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Wa\BackBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }
}
