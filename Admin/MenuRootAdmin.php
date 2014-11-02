<?php

namespace Msi\CmsBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Admin\AdminConfig;
use Msi\AdminBundle\Grid\Grid;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @DI\Service("msi_cms_menu_root_admin", parent="msi_admin.admin")
 * @DI\Tag("msi.admin")
 */
class MenuRootAdmin extends Admin
{
    public function buildConfig(AdminConfig $config)
    {
        $config->setDataClass($this->container->getParameter('msi_cms.menu.class'));
        $config->addChild($this->container->get('msi_cms_menu_node_admin'));
        $config
            ->addOption('form_template', 'MsiCmsBundle:MenuRoot:form.html.twig')
            ->addOption('search_fields', ['a.id', 'a.uniqueName'])
            ->addOption('order_by', ['a.uniqueName' => 'ASC'])
        ;
    }

    public function buildGrid(Grid $builder)
    {
        $builder
            ->add('published', 'boolean')
            ->add('uniqueName')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder->add('uniqueName', 'text', [
            'constraints' => [new NotBlank],
        ]);

        if ($this->container->get('security.context')->getToken()->getUser()->isSuperAdmin()) {
            $builder->add('operators', 'entity', [
                'class' => 'MsiUserBundle:Group',
                'multiple' => true,
                'expanded' => true,
            ]);
        }
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            ->add('published', 'checkbox')
        ;
    }

    public function configureAdminFindAllQuery(QueryBuilder $qb)
    {
        $qb->andWhere('a.lvl = 0');
    }

    public function postLoad(ArrayCollection $collection)
    {
        $this->container->get('msi_admin.bouncer')->operatorFilter($collection);
    }
}
