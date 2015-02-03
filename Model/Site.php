<?php

namespace Msi\CmsBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\MappedSuperclass
 * @UniqueEntity("host")
 */
abstract class Site
{
    use \Msi\AdminBundle\Doctrine\Extension\Model\Translatable;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Regex("/^([a-z0-9]{1}[a-z0-9-]+\.)+[a-z]+$/")
     */
    protected $host;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isDefault;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $css;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $js;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $theme;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->isDefault = false;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    public function getCss()
    {
        return $this->css;
    }

    public function setCss($css)
    {
        $this->css = $css;

        return $this;
    }

    public function getJs()
    {
        return $this->js;
    }

    public function setJs($js)
    {
        $this->js = $js;

        return $this;
    }

    public function getIsDefault()
    {
        return $this->isDefault;
    }

    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string) $this->host;
    }
}
