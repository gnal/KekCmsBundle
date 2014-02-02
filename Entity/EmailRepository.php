<?php

namespace Msi\CmsBundle\Entity;

use Msi\AdminBundle\Entity\EntityRepository;

class EmailRepository extends EntityRepository
{
    public function findAllPublishedByName($name)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->leftJoin('a.translations', 'translations')

            ->andWhere($qb->expr()->eq('translations.published', ':published'))
            ->setParameter('published', true)

            ->andWhere($qb->expr()->eq('a.name', ':name'))
            ->setParameter('name', $name)
        ;

        return $qb->getQuery()->execute();
    }
}
