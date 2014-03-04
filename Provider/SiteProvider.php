<?php

namespace Msi\CmsBundle\Provider;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @DI\Service("msi_cms.site_provider")
 */
class SiteProvider
{
    private $requestStack;
    private $siteManager;

    private $site;

    /**
     * @DI\InjectParams({
     *     "requestStack" = @DI\Inject("request_stack"),
     *     "siteManager" = @DI\Inject("msi_cms.site_manager")
     * })
     */
    public function __construct($requestStack, $siteManager)
    {
        $this->requestStack = $requestStack;
        $this->siteManager = $siteManager;
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
                    throw new NotFoundHttpException('No site was found.');
                }
                $site = $site[0];
            }

            $this->site = $site;
        }

        return $this->site;
    }

    public function hasManySites()
    {
        // should make a count method in custom repo instead of that ;)
        $sites = $this->siteManager->getRepository()->findAll();

        return count($sites);
    }

    public function getWorkingLocale()
    {
        return $this->requestStack->getCurrentRequest()->getSession()->get('working_locale', $this->getSite()->getLocale());
    }
}
