<?php

namespace Msi\CmsBundle\Entity;

use Msi\AdminBundle\Entity\EntityRepository;

class PageRepository extends EntityRepository
{
    public function findCmsPage($site, $slug, $locale)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->join('a.translations', 'translations')
            ->join('a.blocks', 'blocks')

            ->andWhere($qb->expr()->eq('a.site', ':site'))
            ->setParameter('site', $site)

            ->andWhere($qb->expr()->eq('translations.published', ':published'))
            ->setParameter('published', true)

            ->andWhere($qb->expr()->eq('translations.locale', ':locale'))
            ->setParameter('locale', $locale)

            ->andWhere($qb->expr()->eq('translations.slug', ':slug'))
            ->setParameter('slug', $slug)

            ->andWhere($qb->expr()->isNull('a.route'))

            ->addOrderBy('blocks.position', 'ASC')
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findByRoute($route)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->join('a.translations', 'translations')
            ->join('a.blocks', 'blocks')

            ->andWhere($qb->expr()->eq('translations.published', ':published'))
            ->setParameter('published', true)

            ->andWhere($qb->expr()->eq('a.route', ':route'))
            ->setParameter('route', $route)

            ->addOrderBy('blocks.position', 'ASC')
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findAdminFormParentChoices($id)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->leftJoin('a.translations', 'translations')

            ->andWhere($qb->expr()->neq('a.id', ':id'))
            ->setParameter('id', $id)

            ->addOrderBy('translations.title', 'ASC')
        ;

        return $qb->getQuery()->execute();
    }
}
