<?php

namespace Msi\CmsBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Grid\GridBuilder;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\QueryBuilder;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("msi_cms_page_admin", parent="msi_admin.admin")
 * @DI\Tag("msi.admin")
 */
class PageAdmin extends Admin
{
    public function configure()
    {
        $this->options = [
            'form_template' => 'MsiCmsBundle:Page:form.html.twig',
            'search_fields' => ['a.id', 'a.route', 'translations.title'],
            'order_by'      => ['translations.title' => 'ASC'],
        ];

        $this->class = $this->container->getParameter('msi_cms.page.class');
    }

    public function buildGrid(GridBuilder $builder)
    {
        $builder
            ->add('published', 'boolean')
            ->add('title')
        ;

        if ($this->getUser()->isSuperAdmin()) {
            $builder->add('route');
        }
    }

    public function buildForm(FormBuilder $builder)
    {
        if ($this->getUser()->isSuperAdmin()) {
            $collection = $this->container->get('router')->getRouteCollection();
            $choices = [];
            foreach ($collection->all() as $name => $route) {
                if (preg_match('#^_#', $name)) {
                    continue;
                }
                if (preg_match('#^msi_page_#', $name)) {
                    continue;
                }
                $choices[$name] = $name;
            }

            $parentChoices = $this->getRepository()->findAdminFormParentChoices($this->getObject()->getId());

            if (count($this->container->getParameter('msi_cms.page.layouts')) > 1) {
                $builder->add('template', 'choice', ['choices' => $this->container->getParameter('msi_cms.page.layouts')]);
            }

            $builder
                ->add('route', 'choice', [
                    'required' => false,
                    'empty_value' => '',
                    'choices' => $choices,
                ])
                ->add('css', 'textarea')
                ->add('js', 'textarea')
                ->add('parent', 'entity', [
                    'empty_value' => '',
                    'required' => false,
                    'class' => $this->container->getParameter('msi_cms.page.class'),
                    'choices' => $parentChoices,
                ])
                // ->add('blocks', 'collection', [
                //     'type' => new \Msi\AdminBundle\Form\Type\BlockType($this->container),
                //     'allow_add' => true,
                // ])
            ;
        }

        $builder->add('showTitle');

        if ($this->container->getParameter('msi_cms.multisite')) {
            $builder->add('site', 'entity', [
                'class' => $this->container->getParameter('msi_cms.site.class'),
            ]);
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

    // public function buildFilterForm(FormBuilder $builder)
    // {
    //     if ($this->container->getParameter('msi_cms.multisite')) {
    //         $builder->add('site', 'entity', [
    //             'label' => ' ',
    //             'empty_value' => '- Site -',
    //             'class' => $this->container->getParameter('msi_cms.site.class'),
    //         ]);
    //     }

    //     $builder->add('home', 'choice', [
    //         'label' => ' ',
    //         'empty_value' => '- '.$this->container->get('translator')->trans('Home').' -',
    //         'choices' => [
    //             '1' => $this->container->get('translator')->trans('Yes'),
    //             '0' => $this->container->get('translator')->trans('No'),
    //         ],
    //     ]);
    // }

    public function buildListQuery(QueryBuilder $qb)
    {
        $qb->addOrderBy('translations.title', 'ASC');
    }

    public function prePersist($entity)
    {
        if (!$this->container->getParameter('msi_cms.multisite')) {
            $entity->setSite($this->container->get('msi_admin.provider')->getSite());
        }
        // most often theres only one layout so we just remove the input from the form and put it here
        if ($entity->getTemplate() === null) {
            $entity->setTemplate(array_keys($this->container->getParameter('msi_cms.page.layouts'))[0]);
        }
    }
}
