<?php

namespace Msi\CmsBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Admin\AdminConfig;
use Msi\AdminBundle\Grid\Grid;
use Symfony\Component\Form\FormBuilder;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("msi_cms_email_admin", parent="msi_admin.admin")
 * @DI\Tag("msi.admin")
 */
class EmailAdmin extends Admin
{
    public function buildConfig(AdminConfig $config)
    {
        $config->setDataClass($this->container->getParameter('msi_cms.email.class'));
        $config->addOption('form_template', 'MsiCmsBundle:Email:form.html.twig');
        $config->addOption('search_fields', ['a.id', 'a.name', 'a.subject']);
    }

    public function buildGrid(Grid $grid)
    {
        $grid
            ->add('published', 'boolean', [
                'label' => 'Enabled',
            ])
            ->add('name')
            ->add('subject')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $nameAttr = [];
        $availableVarsAttr = [];
        if (!$this->getUser()->isSuperAdmin() && $this->getObject()->getId()) {
            $nameAttr['disabled'] = null;
            $availableVarsAttr['disabled'] = null;
        }

        $builder
            ->add('name', 'text', [
                'attr' => $nameAttr,
            ])
            ->add('fromWho')
            ->add('toWho')
            ->add('cc')
            ->add('bcc')
            ->add('availableVars', 'textarea', [
                'attr' => array_merge([
                    'data-help' => $this->container->get('translator')->trans('availablevars_data_help'),
                ], $availableVarsAttr),
            ])
        ;
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            ->add('subject')
            ->add('body', 'textarea', [
                'attr' => [
                    'class' => 'tinymce',
                ],
            ])
        ;
    }
}
