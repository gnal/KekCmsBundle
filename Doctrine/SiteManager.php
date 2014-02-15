<?php

namespace Msi\CmsBundle\Doctrine;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @DI\Service("msi_cms.site_manager")
 */
class SiteManager extends Manager
{
    /**
     * @DI\InjectParams({
     *     "om" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "class" = @DI\Inject("%msi_cms.site.class%")
     * })
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->class = $class;
    }
}
