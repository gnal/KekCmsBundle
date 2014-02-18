<?php

namespace Msi\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PageController extends Controller
{
    /**
     * @Route("/{_locale}/{slug}")
     * @Template()
     */
    public function showAction()
    {
        $site = $this->container->get('msi_cms.site_provider')->getSite();
        $locale = $this->getRequest()->getLocale();
        $slug = $this->getRequest()->attributes->get('slug');

        $em = $this->container->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository($this->container->getParameter('msi_cms.page.class'));
        $page = $repo->findCmsPage($site, $slug, $locale);

        if (!$page) {
            throw $this->createNotFoundException();
        }

        return [
            'page' => $page,
        ];
    }
}
