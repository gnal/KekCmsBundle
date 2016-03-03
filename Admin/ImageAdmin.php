<?php

namespace Msi\CmsBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Admin\AdminConfig;
use Msi\AdminBundle\Grid\Grid;
use Symfony\Component\Form\FormBuilder;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("msi_cms_image_admin", parent="msi_admin.admin")
 * @DI\Tag("msi.admin")
 */
class ImageAdmin extends Admin
{
    public function buildConfig(AdminConfig $config)
    {
        $config->setDataClass($this->container->getParameter('msi_cms.image.class'));
    }

    public function buildGrid(Grid $builder)
    {
        $builder
            ->add('imageName')
            ->add('image', 'image', [
                'prefix' => 't',
            ])
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('name')
            ->add('imageFile', 'file')
        ;
    }
}
