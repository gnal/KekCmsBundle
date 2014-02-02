<?php

namespace Msi\CmsBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class MenuRepository extends NestedTreeRepository
{
    use \Msi\CmsBundle\Entity\RepositoryMethods;

    public function findAdminFormParentChoices($parentId, $object)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->leftJoin('a.translations', 'translations')
            ->leftJoin('a.children', 'children')

            ->andWhere($qb->expr()->eq('a.menu', ':parent'))
            ->setParameter('parent', $parentId)

            ->addOrderBy('a.lft', 'ASC')
        ;

        if ($object->getId()) {
            $qb->andWhere('a.id != :match')->setParameter('match', $object->getId());
            $i = 0;
            foreach ($object->getChildren() as $child) {
                $qb->andWhere('a.id != :match'.$i)->setParameter('match'.$i, $child->getId());
                $i++;
            }
        }

        if ($object->getChildren()->count()) {
            $qb->andWhere('a.lvl <= :bar')->setParameter('bar', $object->getLvl() - 1);
        }

        $qb->andWhere('a.lvl <= :foo')->setParameter('foo', 2);

        return $qb->getQuery()->execute();
    }
}
