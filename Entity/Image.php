<?php

namespace Msi\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Msi\CmsBundle\Model\Image as BaseImage;

/**
 * @ORM\Entity(repositoryClass="Msi\AdminBundle\Entity\EntityRepository")
 */
class Image extends BaseImage
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
