<?php

namespace Msi\CmsBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Admin\AdminConfig;
use Msi\AdminBundle\Grid\Grid;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Msi\AdminBundle\Form\Type\DynamicType;
use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\ORM\QueryBuilder;

/**
 * @DI\Service("msi_cms_block_admin", parent="msi_admin.admin")
 * @DI\Tag("msi.admin")
 */
class BlockAdmin extends Admin
{
    public function buildConfig(AdminConfig $config)
    {
        $config->setDataClass($this->container->getParameter('msi_cms.block.class'));
        $config
            ->addOption('form_template', 'MsiCmsBundle:Block:form.html.twig')
            ->addOption('search_fields', ['a.id', 'a.type', 'a.name', 'a.slot'])
        ;
    }

    public function buildGrid(Grid $grid)
    {
        $grid
            ->add('published', 'boolean')
            ->add('name')
            ->add('slot')
            ->add('pages', 'Msi\CmsBundle\Grid\Column\BlockPagesColumn')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        // workflow for creating blocks:
        // 1. choose type of block you want to create, text, menu, action, etc
        // 2. once the type has been chosen, it cant be changed, if you fucked up, delete old block and create  a new block

        $builder->add('name');

        if ($this->getObject()->getType()) {
            $blockType = $this->container->get($this->getObject()->getType());
            $settingsFormBuilder = $this->container->get('form.factory')->createBuilder();
            $blockType->buildForm($settingsFormBuilder);
            $settingsFormType = new DynamicType($settingsFormBuilder, 'block_settings');
            if ($settingsFormBuilder->all()) {
                $builder->add('settings', $settingsFormType);
            }

            $builder->add('pages', 'entity', [
                'multiple' => true,
                'expanded' => true,
                'class' => $this->container->getParameter('msi_cms.page.class'),
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->leftJoin('a.translations', 't')
                        ->addSelect('t')
                        ->addOrderBy('t.title', 'ASC')
                    ;
                },
            ]);

            $builder->add('slot', 'choice', ['choices' => $this->container->getParameter('msi_cms.block.slots')]);

            $builder->add('showOnAllPages', 'checkbox');
            $builder->add('sort');
        } else {
            $types = [];
            foreach ($this->container->getServiceIds() as $id) {
                if (preg_match('@^.+_.+\.block\.type\..+$@', $id)) {
                    $types[$id] = $this->container->get($id)->getName();
                }
            }

            $builder->add('type', 'choice', [
                'choices' => $types,
            ]);
        }
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        if ($this->getObject()->getType()) {
            $blockType = $this->container->get($this->getObject()->getType());
            $settingsFormBuilder = $this->container->get('form.factory')->createBuilder();
            $blockType->buildTranslationForm($settingsFormBuilder);
            $settingsFormType = new DynamicType($settingsFormBuilder, 'block_translation_settings');
            if ($settingsFormBuilder->all()) {
                $builder->add('settings', $settingsFormType);
            }

            $builder->add('published', 'checkbox');
        }
    }

    // public function buildFilterForm(FormBuilder $builder)
    // {
    //     $types = [];
    //     foreach ($this->container->getServiceIds() as $id) {
    //         if (preg_match('@^.+_.+\.block\..+$@', $id)) {
    //             $types[$id] = $id;
    //         }
    //     }

    //     $builder
    //         ->add('pages', 'entity', array(
    //             'class' => $this->container->getParameter('msi_cms.page.class'),
    //             'label' => ' ',
    //             'empty_value' => '- '.$this->container->get('translator')->transchoice('entity.Page', 1).' -',
    //             'query_builder' => function(EntityRepository $er) {
    //                 return $er->createQueryBuilder('a')
    //                     ->leftJoin('a.translations', 't')
    //                     ->addSelect('t')
    //                 ;
    //             },
    //         ))
    //         ->add('type', 'choice', array(
    //             'label' => ' ',
    //             'empty_value' => '- Type -',
    //             'choices' => $types,
    //         ))
    //     ;
    // }

    public function configureCrudQueryBuilder(QueryBuilder $qb)
    {
        $qb
            ->leftJoin('a.pages', 'pages')
            ->leftJoin('pages.translations', 'pages_translations')

            ->addOrderBy('a.slot', 'ASC')
            ->addOrderBy('a.sort', 'ASC')

            ->addOrderBy('pages_translations.title', 'ASC')
        ;
    }
}
