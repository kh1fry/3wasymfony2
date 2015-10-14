<?php

namespace Wa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Wa\BackBundle\Repository\ImageRepository")
 */
class Image
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=100)
     */
    private $caption;


    private $file;


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
     * Set name
     *
     * @param string $name
     *
     * @return image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set caption
     *
     * @param string $caption
     *
     * @return image
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile(){
        return $this->file;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return file
     */
    public function setFile(UploadedFile $file){
        $this->file= $file;
        return $this;
    }

    public function upload(){
        $nameImage= $this->file->getClientOriginalName();
        //Se placer au bonne endroit
        $error = $this->file->move(
        __DIR__."/../../../../web/".$this->getRootWebDir(),$nameImage);
        $this->name= $nameImage;
        $this->caption= $nameImage;

       // die("ok");
    }

    public function getWebPath(){
        return $this->getRootWebDir()."/".$this->name;
    }

    private function getRootWebDir(){
        return "uploads/categories";
    }
}

