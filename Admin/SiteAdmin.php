<?php

namespace Msi\CmsBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Admin\AdminConfig;
use Msi\AdminBundle\Grid\Grid;
use Symfony\Component\Form\FormBuilder;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("msi_cms_site_admin", parent="msi_admin.admin")
 * @DI\Tag("msi.admin")
 */
class SiteAdmin extends Admin
{
    public function buildConfig(AdminConfig $config)
    {
        $config->setDataClass($this->container->getParameter('msi_cms.site.class'));
        $config->addOption('form_template', 'MsiCmsBundle:Site:form.html.twig');
        $config->addOption('search_fields', ['a.id', 'a.host', 'translations.brand']);
    }

    public function buildGrid(Grid $builder)
    {
        $builder
            ->add('published', 'boolean')
            ->add('brand', 'edit')
            ->add('host')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('host', 'text', [
                'attr' => [
                    'placeholder' => 'www.example.com',
                ],
            ])
            ->add('isDefault')
            ->add('css', 'textarea')
            ->add('js', 'textarea')
        ;

        if (count($this->container->getParameter('msi_cms.site.themes')) > 0) {
            $builder->add('theme', 'choice', [
                'required' => false,
                'choices' => $this->container->getParameter('msi_cms.site.themes'),
            ]);
        }
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            ->add('brand')
            ->add('metaDescription', 'textarea')
        ;
    }
}
