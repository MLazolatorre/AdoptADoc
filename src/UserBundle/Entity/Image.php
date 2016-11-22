<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Image
 *
 * @ORM\Table(name="image_user")
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
     * @return string
     */
    public function getTempFileName()
    {
        return $this->tempFileName;
    }

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
        return "upload/img/user";
    }

    public function getUploadRootDir ()
    {
        return __DIR__ . "/../../../web/" . $this->getUploadDir();
    }

    public function getWebPath ()
    {
        return $this->getUploadDir() . "/" . $this->id . "." . $this->getExtension();
    }

    /**
     * we use PreFlush end not PreUpdate because we save $file witch is a attribute but not an element
     * of the data base
     * @ORM\PreFlush
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
     * we can use PreUpdate because in the preFlush we changed elements form the data base
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
        $this->tempFileName = $this->getUploadRootDir() . "/" . $this->getId() . "." . $this->getExtension();
    }

    /**
     * @ORM\PostRemove
     */
    public function PostRemoveUpload ()
    {
        if (file_exists($this->tempFileName)){
            unlink($this->tempFileName);
        }
        else{
            throw new NotFoundResourceException("l'image " . $this->tempFileName . " n'a pas été trouvé");
        }
    }



}
