<?php

namespace Wa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="Wa\BackBundle\Repository\CommentaireRepository")
 */
class Commentaire
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
     * @Assert\NotBlank(message="Nom de l'auteur obligatoire")
     * @ORM\Column(name="auteur", type="string", length=100)
     */
    private $auteur;

    /**
     * @var string
     * @Assert\NotBlank(message="Saisir du contenu")
     * @ORM\Column(name="contenu", type="string", length=255)
     */
    private $contenu;

    /**
     * @var integer
     * @Assert\NotBlank(message="Indiquer une note")
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      minMessage = "La note minimum est {{ limit }}",
     *      maxMessage = "La note maximum est {{ limit }}"
     * )
     * @ORM\Column(name="note", type="integer")
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \Integer
     *
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="commentaires")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id", nullable=false)
     *
     */
    private $produit;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;


    public function __construct(){
        $this->dateCreation= new \Datetime("now");
        $this->active= 0;
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
     * Set auteur
     *
     * @param string $auteur
     *
     * @return Commentaire
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Commentaire
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set note
     *
     * @param integer $note
     *
     * @return Commentaire
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return integer
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Commentaire
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set produit
     *
     * @param \Wa\BackBundle\Entity\Produit $produit
     *
     * @return Commentaire
     */
    public function setProduit(\Wa\BackBundle\Entity\Produit $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \Wa\BackBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Commentaire
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
   /* public function isInterdit(ExecutionContextInterface $context)
    {
        //$tabMotsInterdit=[""];
        if (preg_match("/\b(con|connard)\b/i",$this->contenu)){
            $context->buildViolation('Votre commentaire comprend des mots interdits')
                ->atPath('contenu')
                ->addViolation();
        }
    }*/
}
