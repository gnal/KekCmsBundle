<?php

namespace Msi\CmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('msi_cms');

        $this->addSiteSection($rootNode);
        $this->addEmailSection($rootNode);
        $this->addMenuSection($rootNode);
        $this->addPageSection($rootNode);
        $this->addBlockSection($rootNode);
        $this->addAdminSection($rootNode);
        $this->addConfigSection($rootNode);
        $this->addHelpSection($rootNode);
        $this->addImageSection($rootNode);

        return $treeBuilder;
    }

    private function addImageSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('image_class')
                    ->defaultValue('Msi\CmsBundle\Entity\Image')
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }

    private function addSiteSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('site_class')
                    ->defaultValue('Msi\CmsBundle\Entity\Site')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('site_themes')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end();
    }

    private function addConfigSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('config_class')
                    ->defaultValue('Msi\CmsBundle\Entity\Config')
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }

    private function addHelpSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('help_class')
                    ->defaultValue('Msi\CmsBundle\Entity\Help')
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }

    private function addEmailSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('email_class')
                    ->defaultValue('Msi\CmsBundle\Entity\Email')
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }

    private function addMenuSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('menu_class')
                    ->defaultValue('Msi\CmsBundle\Entity\Menu')
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }

    private function addPageSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('page_class')
                    ->defaultValue('Msi\CmsBundle\Entity\Page')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('page_layouts')
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end();
    }

    private function addBlockSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('block_class')
                    ->defaultValue('Msi\CmsBundle\Entity\Block')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('block_actions')
                    ->defaultValue([])
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('block_templates')
                    ->defaultValue([])
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('block_slots')
                    ->defaultValue([])
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;
    }

    protected function addAdminSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('site_admin')->defaultValue('Msi\CmsBundle\Admin\SiteAdmin')->cannotBeEmpty()->end()
                ->scalarNode('menu_root_admin')->defaultValue('Msi\CmsBundle\Admin\MenuRootAdmin')->cannotBeEmpty()->end()
                ->scalarNode('menu_node_admin')->defaultValue('Msi\CmsBundle\Admin\MenuNodeAdmin')->cannotBeEmpty()->end()
                ->scalarNode('page_admin')->defaultValue('Msi\CmsBundle\Admin\PageAdmin')->cannotBeEmpty()->end()
                ->scalarNode('block_admin')->defaultValue('Msi\CmsBundle\Admin\BlockAdmin')->cannotBeEmpty()->end()
            ->end()
        ;
    }
}
