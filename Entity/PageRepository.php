<?php

namespace Msi\CmsBundle\Entity;

use Msi\AdminBundle\Entity\EntityRepository;

class PageRepository extends EntityRepository
{
    public function findCmsPage($site, $slug = null, $locale, $route = null)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->leftJoin('a.translations', 'translations')
            ->leftJoin('a.blocks', 'blocks')

            ->andWhere($qb->expr()->eq('a.site', ':site'))
            ->setParameter('site', $site)

            ->andWhere($qb->expr()->eq('translations.published', ':published'))
            ->setParameter('published', true)

            ->andWhere($qb->expr()->eq('translations.locale', ':locale'))
            ->setParameter('locale', $locale)

            ->addOrderBy('blocks.sort', 'ASC')
        ;

        if ($slug !== null) {
            $qb
                ->andWhere($qb->expr()->eq('translations.slug', ':slug'))
                ->setParameter('slug', $slug)
            ;
        }

        if ($route === null) {
            $qb->andWhere($qb->expr()->isNull('a.route'));
        } else {
            $qb
                ->andWhere($qb->expr()->eq('a.route', ':route'))
                ->setParameter('route', $route)
            ;
        }

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
