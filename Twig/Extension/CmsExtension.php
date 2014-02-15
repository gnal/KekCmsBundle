<?php

namespace Msi\CmsBundle\Twig\Extension;

class CmsExtension extends \Twig_Extension
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'msi_block_render' => new \Twig_Function_Method($this, 'renderBlock', array('is_safe' => array('html'))),
        );
    }

    public function getGlobals()
    {
        $globals = [];
        if (!$this->container->isScopeActive('request')) {
            return $globals;
        }
        $request = $this->container->get('request');
        $workingLocale = $this->container->get('msi_cms.site_provider')->getWorkingLocale();

        $site = $this->container->get('msi_cms.site_provider')->getSite();
        $globals['site'] = $site;

        $globals['is_multisite'] = $this->container->get('msi_cms.site_provider')->hasManySites();
        $globals['tiny_mce_template'] = $this->container->getParameter('msi_admin.tiny_mce');

        $pageClass = $this->container->getParameter('msi_cms.page.class');
        $page = $this->container->get('doctrine')->getRepository($pageClass)->findByRoute($request->attributes->get('_route'));
        if (!$page) {
            $page = new $pageClass();
            $page->createTranslation($workingLocale);
        }
        $globals['page'] = $page;

        return $globals;
    }

    public function renderBlock($slot, $page)
    {
        $content = '';
        foreach ($page->getBlocks() as $block) {
            if ($block->getRendered() === true) {
                continue;
            }
            if (!$block->getTranslation()->getPublished()) {
                continue;
            }
            if ($block->getSlot() !== $slot) {
                continue;
            }
            $handler = $this->container->get($block->getType());
            $content .= $handler->execute($block, $page);
            $block->setRendered(true);
        }

        return $content;
    }

    public function getName()
    {
        return 'msi_cms_cms';
    }
}
