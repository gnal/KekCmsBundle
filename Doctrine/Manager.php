<?php

namespace Msi\CmsBundle\Doctrine;

abstract class Manager
{
    protected $objectManager;
    protected $class;

    public function getRepository()
    {
        return $this->objectManager->getRepository($this->class);
    }

    public function getClass()
    {
        return $this->class;
    }
}
