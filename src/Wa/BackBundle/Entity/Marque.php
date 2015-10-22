<?php

namespace Wa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Wa\BackBundle\Validator\AntiGrosMots;

/**
 * Marque
 *
 * @ORM\Table(name="marque")
 * @ORM\Entity(repositoryClass="Wa\BackBundle\Repository\MarqueRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Marque
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
     * @ORM\Column(name="titre", type="string", length=100)
     * @Assert\NotBlank(message="Remplir champs titre")
     * @AntiGrosMots
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_Update", type="datetime")
     */
    private $dateUpdate;

    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="marques", cascade={"persist"})
     * @ORM\JoinTable(name="marque_tag",
     *                joinColumns={
     *                      @ORM\JoinColumn(name="marque_id", referencedColumnName="id")
     *                },
     *                inverseJoinColumns={
     *                      @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *                }
     *
     * )
     */
    private $tags;
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Marque
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return Marque
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateMarque(){
        //"\" signifie que la classe n'appartient pas au namespace
        $this->dateUpdate= new \DateTime();
    }
    

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Marque
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tag
     *
     * @param \Wa\BackBundle\Entity\Tag $tag
     *
     * @return Marque
     */
    public function addTag(\Wa\BackBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Wa\BackBundle\Entity\Tag $tag
     */
    public function removeTag(\Wa\BackBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }


}
