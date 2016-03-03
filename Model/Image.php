<?php

namespace Msi\CmsBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass
 */
abstract class Image
{
    use \Msi\AdminBundle\Doctrine\Extension\Model\Timestampable;
    use \Msi\AdminBundle\Doctrine\Extension\Model\Uploadable;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $image;

    protected $imageFile;

    protected $imageTemp;

    public function getUploadFields()
    {
        return [
            'image' => 'images',
        ];
    }

    public function getImageName()
    {
        return $this->image;
    }

    public function getDatePrefix()
    {
        return false;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    public function getImageTemp()
    {
        return $this->imageTemp;
    }

    public function setImageTemp($imageTemp)
    {
        $this->imageTemp = $imageTemp;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function getId()
    {
        return $this->id;
    }
}
