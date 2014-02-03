<?php

namespace Msi\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Msi\CmsBundle\Model\Block as BaseBlock;

/**
 * @ORM\Entity(repositoryClass="Msi\AdminBundle\Entity\EntityRepository")
 */
class Block extends BaseBlock
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
