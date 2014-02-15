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
        $object = $event->getObject();

        $class = $this->pageManager->getClass();
        if (!$object instanceof $class) {
            return;
        }

        if (!$this->provider->hasManySites()) {
            $object->setSite($this->provider->getSite());
        }

        // most often theres only one layout so we just remove the input from the form and set it here
        if ($object->getTemplate() === null) {
            $object->setTemplate(array_keys($this->pageManager->getLayouts())[0]);
        }
    }
}
