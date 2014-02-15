<?php

namespace Msi\CmsBundle\Doctrine;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @DI\Service("msi_cms.page_manager")
 */
class PageManager extends Manager
{
    protected $layouts;

    /**
     * @DI\InjectParams({
     *     "om" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "class" = @DI\Inject("%msi_cms.page.class%"),
     *     "layouts" = @DI\Inject("%msi_cms.page.layouts%")
     * })
     */
    public function __construct(ObjectManager $om, $class, $layouts)
    {
        $this->objectManager = $om;
        $this->class = $class;

        $this->layouts = $layouts;
    }

    public function getLayouts()
    {
        return $this->layouts;
    }
}
