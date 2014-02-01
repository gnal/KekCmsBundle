<?php

namespace Msi\CmsBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Grid\GridBuilder;
use Symfony\Component\Form\FormBuilder;

class EmailAdmin extends Admin
{
    public function configure()
    {
        $this->options['form_template'] = 'MsiCmsBundle:Email:form.html.twig';
        $this->options['search_fields'] = ['a.id', 'a.name', 'a.subject'];
    }

    public function buildGrid(GridBuilder $builder)
    {
        $builder
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
