<?php

namespace Msi\CmsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuListener implements EventSubscriber
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(EventArgs $e)
    {
        $metadata = $e->getClassMetadata();

        if ($metadata->name !== $this->container->getParameter('msi_cms.menu.class')) {
            return;
        }

        if (!$metadata->hasAssociation('page')) {
            $metadata->mapManyToOne([
                'fieldName'    => 'page',
                'targetEntity' => $this->container->getParameter('msi_cms.page.class'),
                'joinColumns' => [
                    [
                        'onDelete' => 'SET NULL',
                    ],
                ],
            ]);
        }
    }
}
