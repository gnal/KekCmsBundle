<?php

namespace Msi\CmsBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class MenuTranslation
{
    use \Msi\AdminBundle\Doctrine\Extension\Model\Translation;
    use \Msi\AdminBundle\Doctrine\Extension\Model\Publishable;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $route;

    public function getId()
    {
        return $this->id;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    public function getRealRoute()
    {
        if (preg_match('#^@#', $this->getRoute())) {
            return substr($this->getRoute(), 1);
        }

        return false;
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
}
