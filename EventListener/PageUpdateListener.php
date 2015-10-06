<?php

namespace Msi\CmsBundle\EventListener;

use JMS\DiExtraBundle\Annotation as DI;

use Msi\AdminBundle\MsiAdminEvents;
use Msi\AdminBundle\Event\ObjectEvent;

/**
 * @DI\Service
 */
class PageUpdateListener
{
    private $provider;
    private $pageManager;

    /**
     * @DI\InjectParams({
     *     "provider" = @DI\Inject("msi_cms.site_provider"),
     *     "pageManager" = @DI\Inject("msi_cms.page_manager")
     * })
     */
    public function __construct($provider, $pageManager)
    {
        $this->provider = $provider;
        $this->pageManager = $pageManager;
    }

    /**
     * @DI\Observe(MsiAdminEvents::OBJECT_CREATE_SUCCESS)
     */
    public function onObjectCreateSuccess(ObjectEvent $event)
    {
        $this->setDefaultValues($event);
    }

    /**
     * @DI\Observe(MsiAdminEvents::OBJECT_UPDATE_SUCCESS)
     */
    public function onObjectUpdateSuccess(ObjectEvent $event)
    {
        $this->setDefaultValues($event);
    }

    private function setDefaultValues($event)
    {
        $object = $event->getObject();

        $class = $this->pageManager->getClass();
        if (!$object instanceof $class) {
            return;
        }

        // set default site

        if (!$this->provider->hasAtLeastOneSite()) {
            die('you need to create at least one site to do that');
        }

        if (!$this->provider->hasManySites()) {
            $object->setSite($this->provider->getSite());
        }
    }
}
