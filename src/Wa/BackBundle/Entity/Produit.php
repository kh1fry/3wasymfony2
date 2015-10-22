<?php

namespace Wa\BackBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="Wa\BackBundle\Repository\ProduitRepository")
 */
class Produit
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
     * @Assert\NotBlank(message="Remplir champs titre")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \Integer
     *
     * @ORM\Column(name="quantity", type="integer", options={"default"=1})
     */
    private $quantity;

    /**
     * @var \Integer
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $categorie;

    /**
     * @var \Integer
     * @Assert\NotBlank(message="Choisir une marque")
     * @ORM\ManyToOne(targetEntity="Marque")
     * @ORM\JoinColumn(name="marque_id", referencedColumnName="id", nullable=false)
     */
    private $marque;

    /**
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="produit", cascade={"persist", "remove"})
     */
    private $commentaires;

    public function __construct(){
        $this->dateCreated= new \Datetime("now");
        $this->quantity= 1;
        $this->commentaires = new ArrayCollection();
    }

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
     * @return Produit
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
     * @return Produit
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
     * Set price
     *
     * @param float $price
     *
     * @return Produit
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Produit
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Produit
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }



    /**
     * Set categorie
     *
     * @param \Wa\BackBundle\Entity\Categorie $categorie
     *
     * @return Produit
     */
    public function setCategorie(\Wa\BackBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \Wa\BackBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }


    /**
     * Set marque
     *
     * @param \Wa\BackBundle\Entity\Marque $marque
     *
     * @return Produit
     */
    public function setMarque(\Wa\BackBundle\Entity\Marque $marque)
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return \Wa\BackBundle\Entity\Marque
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Add commentaire
     *
     * @param \Wa\BackBundle\Entity\Commentaire $commentaire
     *
     * @return Produit
     */
    public function addCommentaire(\Wa\BackBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires[] = $commentaire;

        return $this;
    }

    /**
     * Remove commentaire
     *
     * @param \Wa\BackBundle\Entity\Commentaire $commentaire
     */
    public function removeCommentaire(\Wa\BackBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires->removeElement($commentaire);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }


}
