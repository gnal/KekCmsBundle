<?php

namespace Msi\CmsBundle\Admin;

use Msi\AdminBundle\Admin\Admin;
use Msi\AdminBundle\Grid\Grid;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @DI\Service("msi_cms_menu_node_admin", parent="msi_admin.admin")
 * @DI\Tag("msi.admin")
 */
class MenuNodeAdmin extends Admin
{
    public function configure()
    {
        $this->options = [
            'template_index' => 'MsiCmsBundle:MenuNode:index.html.twig',
            'form_template' => 'MsiCmsBundle:MenuNode:form.html.twig',
            'order_by' => [],
            'search_fields' => ['a.id', 'translations.name'],
        ];

        $this->class = $this->container->getParameter('msi_cms.menu.class');
        $this->setParent($this->container->get('msi_cms_menu_root_admin'));
    }

    public function buildGrid(Grid $builder)
    {
        $builder
            ->add('published', 'boolean')
            ->add('name', 'tree')
        ;
    }

    public function buildForm(FormBuilder $builder)
    {
        $parent = $this->container->get('request')->query->get('parent');

        $parentChoices = $this->getRepository()->findAdminFormParentChoices($parent, $this->getObject());

        $builder
            ->add('page', 'entity', [
                'required' => false,
                // 'empty_value' => 'choisir une page',
                'class' => $this->container->getParameter('msi_cms.page.class'),
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->leftJoin('a.translations', 't')
                        ->orderBy('t.title', 'ASC')
                    ;
                },
            ])
            ->add('parent', 'entity', [
                'class' => $this->container->getParameter('msi_cms.menu.class'),
                'choices' => $parentChoices,
                'property' => 'toTree',
            ])
            ->add('targetBlank', 'checkbox')
            ->add('attr', 'text')
        ;
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
            ->add('name', 'text', [
                'constraints' => [new NotBlank],
            ])
            ->add('route', 'text', ['label' => 'Url'])
        ;
    }

    public function configureAdminFindAllQuery(QueryBuilder $qb)
    {
        // $qb->resetDQLPart('where');
        $qb
            ->andWhere('a.menu = :parent')
            ->setParameter('parent', $this->getParentObject())
        ;
        $qb->andWhere('a.lvl != 0');
        $qb->addOrderBy('a.lft', 'ASC');
    }

    // these methods are not called anymore, use listener instead >:)
    public function prePersist($entity)
    {
        $this->validateRoute($entity);
    }

    // these methods are not called anymore, use listener instead >:)
    public function preUpdate($entity)
    {
        $this->validateRoute($entity);
    }

    public function postLoad(ArrayCollection $collection)
    {
        $this->container->get('msi_admin.bouncer')->operatorFilter($collection);
    }

    // need to make a listener for that
    public function validateRoute($entity)
    {
        if (!preg_match('#^@#', $entity->getTranslation()->getRoute())) {
            return true;
        }

        $collection = $this->container->get('router')->getRouteCollection();
        foreach ($collection->all() as $name => $route) {
            if ($entity->getTranslation()->getRoute() === '@'.$name) {
                return true;
            }
        }

        // throw new \InvalidArgumentException('Route '.$entity->getTranslation()->getRoute().' doesn\'t exist');
        foreach ($entity->getTranslations() as $translation) {
            $translation->setRoute('#INVALID_ROUTE');
        }
    }
}
