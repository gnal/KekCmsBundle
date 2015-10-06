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
        $request = $this->container->get('request_stack')->getCurrentRequest();

        $site = $this->container->get('msi_cms.site_provider')->getSite();
        $globals['site'] = $site;

        $config = $this->container->get('msi_cms.config_provider')->getConfig();
        $globals['config'] = $config;

        $globals['is_multisite'] = $this->container->get('msi_cms.site_provider')->hasManySites();

        $pageClass = $this->container->getParameter('msi_cms.page.class');
        $page = $this->container->get('doctrine')->getRepository($pageClass)->findCmsPage($site, null, $request->getLocale(), $request->attributes->get('_route'));

        if (!$page) {
            $page = new $pageClass();
            $page->createTranslation($request->getLocale());
        }

        $globals['page'] = $page;

        return $globals;
    }

    public function renderBlock($slot, $page)
    {
        $content = '';

        foreach ($page->getBlocks() as $block) {
           $content .= $this->resolveBlock($block, $slot, $page);
        }

        foreach ($this->container->get('msi_cms.block_provider')->getAllPagesBlocks() as $block) {
           $content .= $this->resolveBlock($block, $slot, $page);
        }

        return $content;
    }

    private function resolveBlock($block, $slot, $page)
    {
        if ($block->getRendered() === true) {
            return;
        }

        if (!$block->getTranslation()->getPublished()) {
            return;
        }

        if ($block->getSlot() !== $slot) {
            return;
        }

        $blockType = $this->container->get($block->getType());

        $block->setRendered(true);

        return $blockType->execute($block, $page);
    }

    public function getName()
    {
        return 'msi_cms_cms';
    }
}
