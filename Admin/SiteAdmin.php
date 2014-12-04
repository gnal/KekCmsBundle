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
            ->add('brand')
            ->add('host')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('host', 'text', [
                'attr' => [
                    'data-help' => 'Pro tip: Enter the correct host name instead of relying of the "isDefault" field to reduce number of database queries.',
                    'placeholder' => 'www.example.com',
                ],
            ])
            ->add('isDefault')
            ->add('css', 'textarea')
            ->add('js', 'textarea')
        ;
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            ->add('brand')
            ->add('metaDescription', 'textarea')
        ;
    }
}
