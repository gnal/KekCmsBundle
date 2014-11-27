<?php

namespace Msi\CmsBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Admin\AdminConfig;
use Msi\AdminBundle\Grid\Grid;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\QueryBuilder;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("msi_cms_page_admin", parent="msi_admin.admin")
 * @DI\Tag("msi.admin")
 */
class PageAdmin extends Admin
{
    public function buildConfig(AdminConfig $config)
    {
        $config->setDataClass($this->container->getParameter('msi_cms.page.class'));
        $config
            ->addOption('form_template', 'MsiCmsBundle:Page:form.html.twig')
            ->addOption('search_fields', ['a.id', 'a.route', 'translations.title'])
            ->addOption('order_by', ['translations.title' => 'ASC'])
        ;
    }

    public function buildGrid(Grid $builder)
    {
        $builder
            ->add('published', 'boolean')
            ->add('title')
        ;

        // if ($this->getUser()->isSuperAdmin()) {
        //     $builder->add('slug');
        //     $builder->add('route');
        // }
    }

    public function buildForm(FormBuilder $builder)
    {
        if ($this->getUser()->isSuperAdmin()) {
            $collection = $this->container->get('router')->getRouteCollection();
            $routeChoices = [];
            foreach ($collection->all() as $name => $route) {
                if (preg_match('#^_#', $name)) {
                    continue;
                }
                $routeChoices[$name] = $name;
            }

            $parentChoices = $this->getRepository()->findAdminFormParentChoices($this->getObject()->getId());

            if (count($this->container->getParameter('msi_cms.page.layouts')) > 1) {
                $builder->add('template', 'choice', ['choices' => $this->container->getParameter('msi_cms.page.layouts')]);
            }

            $builder
                ->add('route', 'choice', [
                    'required' => false,
                    'choices' => $routeChoices,
                ])
                ->add('css', 'textarea')
                ->add('js', 'textarea')
                ->add('parent', 'entity', [
                    'required' => false,
                    'class' => $this->container->getParameter('msi_cms.page.class'),
                    'choices' => $parentChoices,
                ])
            ;
        }

        if ($this->container->get('msi_cms.site_provider')->hasManySites()) {
            $builder->add('site');
        }
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            ->add('title')
            ->add('published', 'checkbox')
            ->add('body', 'textarea', [
                'attr' => [
                    'class' => 'tinymce',
                ],
            ])
            ->add('metaTitle')
            ->add('metaDescription', 'textarea')
        ;
    }

    public function buildListQuery(QueryBuilder $qb)
    {
        $qb->addOrderBy('translations.title', 'ASC');
    }
}
