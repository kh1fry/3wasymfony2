<?php

namespace Wa\BackBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Wa\BackBundle\Repository\ImageRepository")
 *
 * @ORM\HasLifecycleCallbacks
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

    /**
    * @Assert\File(
    *     maxSize = "500k",
    *     mimeTypes = {"image/png", "image/jpg", "image/gif","image/jpeg"},
    *     mimeTypesMessage = "Le fichier doit être de type jpeg, png ou gif"
    * )
    */
    private $file;

    private $thumbnails =
        [
            'thumb-small' => [100,100],
            'thumb-medium' => [400,400],
            'thumb-large' => [800,800],
        ];

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
    // propriété permettant de sauvegarde l'ancien nom de l'image (lorsqu'on fait une édition)
    private $oldName;
    /**
     * Set file
     *
     * @param string $file
     *
     * @return file
     */
    public function setFile(UploadedFile $file){
        $this->file= $file;

        // Si j'ai déjà un nom (édition), je sauvegarde celui-ci dans une propriété oldName
        if(null != $this->name){

            $this->oldName=$this->name;
            // On effectue une modification fictive pour obliger doctrine à croire qu'il y a eu une modif et donc
            // faire la mise à jour de mon objet Image
            $this->name = "changement";
        }
        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function upload(){
        //Si pas d'image au revoir
        if(null==$this->file){
            return;
        }
        //die('upload');
        //UPLOAD IMG
        //$nameImage= $this->file->getClientOriginalName();
        //Rendre l'img unique dans la bdd au cas ou 2 personnes télécharge la même img
        $nameImage = str_replace('.','',uniqid("", true)).'.'.$this->file->guessExtension();
        //die(dump($nameImage));
        //Se placer au bonne endroit
        $this->file->move(__DIR__."/../../../../web/".$this->getRootWebDir(),$nameImage);
        $this->name= $nameImage;

        // Creation des thumbnails
        $imagine = new \Imagine\Gd\Imagine();
        $imagineOpen = $imagine->open(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameImage);
        $mode1    = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        $mode2    = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

        foreach($this->thumbnails as $nameThumb=>$size)
        {
          $imagineOpen->thumbnail(new \Imagine\Image\Box($size[0],$size[1]), $mode1)
              ->save(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameThumb.'-'.$nameImage);
        }
        // Suppression de l'ancienne image

        if (!empty($this->oldName))
        {
            //Si le fichier existe
            if(file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$this->oldName)){
                //Je le supprime
                unlink(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$this->oldName);
            }
            //Pour chaque thumbnails suppprimer les photos correspondantes
            foreach($this->thumbnails as $nameThumb => $size)
            {
                if (file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameThumb.'-'.$this->oldName))
                {
                    unlink(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameThumb.'-'.$this->oldName);
                }
            }
        }
    }

    public function getWebPath($thumb = null){
        if($thumb){
            if(file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$thumb.'-'.$this->name)){
               return $this->getRootWebDir().'/'.$thumb.'-'.$this->name;
            }
        }
        if (file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$this->name))
        {
            return $this->getRootWebDir().'/'.$this->name;
        }
        //Ou photo par défaut
        return null;
    }

    //Fonction pour factoriser le chemin
    private function getRootWebDir(){
        return "uploads/categories";
    }

    /**
     * @Assert\Callback
     */
    public function captionValidate(ExecutionContextInterface $context){
        //Gestion de la contrainte caption
        if(($this->file)&&(!$this->caption)){
            $context->buildViolation('Vous devez mettre une légende')
                ->atPath('caption')
                ->addViolation();
        }
    }

    /**
     * Méthode permettant d'éviter d'avoir l'objet proxy dans la méthode removeImage()
     * J'ai ainsi réellement l'objet image accessible dans la méthode removeImage()
     * @ORM\PreRemove
     */
    public function preRemoveImage()
    {
    }

    /**
     * @ORM\PostRemove
     */
    public function removeImage(){
        if (file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$this->name))
        {
            unlink(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$this->name);
        }

        foreach($this->thumbnails as $nameThumb => $size)
        {
            if (file_exists(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameThumb.'-'.$this->name))
            {
                unlink(__DIR__.'/../../../../web/'.$this->getRootWebDir().'/'.$nameThumb.'-'.$this->name);
            }
        }
    }
}

