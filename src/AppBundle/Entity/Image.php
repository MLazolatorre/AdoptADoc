<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var string
     */
    private $tempFileName;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        //check if there has already a image
        if ($this->alt != null){        //if alt != null an image was already save
            $this->tempFileName = $this->extension; //save the extension to delete it later
        }
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getUploadDir ()
    {
        return "/upload/img";
    }

    public function getUploadRootDir ()
    {
        return __DIR__ . "/../../../web/" . $this->getUploadDir();
    }

    public function getWebPath ()
    {
        return $this->getUploadRootDir() . "/" . $this->getId() . "." . $this->getExtension();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function preUpload ()
    {
        //check if there is an uploaded file
        if ($this->file == null){
            return;
        }
        //save the file name before persist to save it in the database
        $this->alt = $this->getFile()->getClientOriginalName();
        $this->extension = $this->getFile()->guessExtension();
    }

    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function PostUpload ()
    {
        //check if there is an uploaded file
        if ($this->file == null){
            return;
        }

        //if there has an image before we delete it
        if ($this->tempFileName != null){
            $oldFile = $this->getUploadRootDir() . "/" . $this->getId() . "." . $this->getExtension();
            if (file_exists($oldFile)){     //check if the file exist
                unlink($oldFile);
            }
        }

        //move the file in the imgDirectory
        $this->file->move(
            $this->getUploadRootDir(),      //the destination DIR
            $this->getId() . '.' . $this->getExtension()    //the name of the new file 'id.extension'
        );
    }

    /**
     * @ORM\PreRemove
     */
    public function PreRemoveUpload ()
    {
        $this->tempFileName = $this->getUploadRootDir() . "/" . $this->getId() . "." . $this->getId();
    }

    /**
     * @ORM\PostRemove
     */
    public function PostRemoveUpload ()
    {
        if (file_exists($this->tempFileName)){
            unlink($this->tempFileName);
        }
        $this->tempFileName = null;
    }



}
