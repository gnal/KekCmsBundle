<?php

namespace Msi\CmsBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Admin\AdminConfig;
use Msi\AdminBundle\Grid\Grid;
use Symfony\Component\Form\FormBuilder;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("msi_cms_config_admin", parent="msi_admin.admin")
 * @DI\Tag("msi.admin")
 */
class ConfigAdmin extends Admin
{
    public function buildConfig(AdminConfig $config)
    {
        $config->setDataClass($this->container->getParameter('msi_cms.config.class'));
    }

    public function buildGrid(Grid $builder)
    {
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('locales', 'locale', [
                'multiple' => true,
                'attr' => [
                    'class' => 'chosen form-control',
                ],
            ])
        ;
    }
}
