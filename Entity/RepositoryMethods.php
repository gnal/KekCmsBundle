<?php

namespace Msi\CmsBundle\Entity;

use Symfony\Component\HttpFoundation\Request;
use Msi\AdminBundle\Admin\Admin;

trait RepositoryMethods
{
    public function getAdminFindAllQueryBuilder(Admin $admin, Request $request)
    {
        $qb = $this->createQueryBuilder('a');

        // traits

        if ($admin->hasTrait('Sortable')) {
            $qb->orderBy('a.position', 'ASC');
        }

        if ($admin->hasTrait('Translatable')) {
            $qb->leftJoin('a.translations', 'translations');
        }

        if ($admin->hasTrait('SoftDeletable')) {
            $qb->andWhere($qb->expr()->isNull('a.deletedAt'));
        }

        // nested crud

        if ($admin->hasParent() && $parent = $request->query->get('parent')) {
            $meta = $this->getEntityManager()->getClassMetadata($admin->getClass());
            foreach ($meta->associationMappings as $association) {
                if (in_array($association['type'], [8, 2]) && $association['targetEntity'] === $admin->getParent()->getClass()) {
                    $relation = $association;
                }
            }
            // must test this code
            if ($relation['type'] === 8) {
                $qb->leftJoin('a.'.$relation['fieldName'], $relation['fieldName']);
                $qb->andWhere($qb->expr()->eq($relation['fieldName'].'.id', ':parent'));
            } else {
                // $qb->andWhere($qb->expr()->eq('a.'.$relation['fieldName'], ':parent'));
                $qb->leftJoin('a.'.$relation['fieldName'], $relation['fieldName']);
                $qb->andWhere($qb->expr()->eq($relation['fieldName'].'.id', ':parent'));
            }
            $qb->setParameter('parent', $parent);
        }

        // search

        if ($q = $request->query->get('q')) {
            $q = trim($q);

            $orX = $qb->expr()->orX();

            $i = 1;
            foreach ($searchFields as $field) {
                $token = 'likeMatch'.$i;
                $orX->add($qb->expr()->like($field, ':'.$token));
                $qb->setParameter($token, '%'.$q.'%');
                $i++;
            }

            $orX->add($qb->expr()->eq('a.id', ':eqMatchId'));
            $qb->setParameter('eqMatchId', $q);

            $qb->andWhere($orX);
        }

        return $qb;
    }
}
