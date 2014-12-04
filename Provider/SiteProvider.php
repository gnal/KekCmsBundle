<?php

namespace Msi\CmsBundle\Provider;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("msi_cms.site_provider")
 */
class SiteProvider
{
    private $requestStack;
    private $siteManager;
    private $defaultLocale;

    private $site;

    /**
     * @DI\InjectParams({
     *     "requestStack" = @DI\Inject("request_stack"),
     *     "siteManager" = @DI\Inject("msi_cms.site_manager"),
     *     "defaultLocale" = @DI\Inject("%locale%")
     * })
     */
    public function __construct($requestStack, $siteManager, $defaultLocale)
    {
        $this->requestStack = $requestStack;
        $this->siteManager = $siteManager;
        $this->defaultLocale = $defaultLocale;
    }

    public function getSite()
    {
        $repo = $this->siteManager->getRepository();

        if (!$this->site) {
            $site = $repo->findOneBy(['host' => $this->requestStack->getCurrentRequest()->getHost()]);
            // if no site, try to fetch one with isDefault true, otherwise throw 404
            if (!$site) {
                $site = $this->siteManager->getRepository()->findBy(['isDefault' => true]);
                if (!isset($site[0])) {
                    return new \Msi\CmsBundle\Entity\Site;
                }
                $site = $site[0];
            }

            $this->site = $site;
        }

        return $this->site;
    }

    public function hasAtLeastOneSite()
    {
        // should make a count method in custom repo instead of that ;)
        $sites = $this->siteManager->getRepository()->findAll();

        return count($sites) > 0;
    }

    public function hasManySites()
    {
        // should make a count method in custom repo instead of that ;)
        $sites = $this->siteManager->getRepository()->findAll();

        return count($sites) > 1;
    }

    // not sure why this is here
    public function getWorkingLocale()
    {
        return $this->requestStack->getCurrentRequest()->getSession()->get('working_locale', $this->defaultLocale);
    }
}
