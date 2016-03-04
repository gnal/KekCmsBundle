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
        ;
    }

    public function buildGrid(Grid $builder)
    {
        $builder
            ->add('uniqueName')
            ->add('published', 'boolean')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $builder->add('uniqueName', 'text', [
            'constraints' => [new NotBlank],
        ]);
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder
            // why do we need to explicitly say checkbox here? mystery...
            ->add('published', 'checkbox')
        ;
    }

    public function configureCrudQueryBuilder(QueryBuilder $qb)
    {
        $qb->andWhere($qb->expr()->eq('a.lvl', ':a_lvl'));
        $qb->setParameter('a_lvl', 0);
    }
}
