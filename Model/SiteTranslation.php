<?php

namespace Msi\CmsBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\MappedSuperclass
 */
abstract class SiteTranslation
{
    use \Msi\AdminBundle\Doctrine\Extension\Model\Translation;
    use \Msi\AdminBundle\Doctrine\Extension\Model\Publishable;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $metaDescription;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $brand;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $offlineMessage;

    public function getOfflineMessage()
    {
        return $this->offlineMessage;
    }

    public function setOfflineMessage($offlineMessage)
    {
        $this->offlineMessage = $offlineMessage;

        return $this;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }
}
