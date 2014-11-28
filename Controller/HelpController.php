<?php

namespace Msi\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HelpController extends Controller
{
    /**
     * @Route("/{_locale}/admin/tech-support")
     * @Template()
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository($this->container->getParameter('msi_cms.help.class'));

        $qb = $repo->createQueryBuilder('a');
        $qb
            ->leftJoin('a.translations', 'a_translations')

            ->andWhere($qb->expr()->eq('a_translations.published', ':a_translations_published'))
            ->setParameter('a_translations_published', true)

            ->addOrderBy('a.position', 'ASC')
        ;

        $entities = $qb->getQuery()->execute();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * @Route("/{_locale}/admin/tech-support/{slug}")
     * @Template()
     */
    public function showAction()
    {
        $repo = $this->getDoctrine()->getRepository($this->container->getParameter('msi_cms.help.class'));

        $qb = $repo->createQueryBuilder('a');
        $qb
            ->leftJoin('a.translations', 'a_translations')

            ->andWhere($qb->expr()->eq('a_translations.published', ':a_translations_published'))
            ->setParameter('a_translations_published', true)

            ->andWhere($qb->expr()->eq('a_translations.slug', ':a_translations_slug'))
            ->setParameter('a_translations_slug', $this->getRequest()->attributes->get('slug'))
        ;

        $entity = $qb->getQuery()->getOneOrNullResult();

        return [
            'entity' => $entity,
        ];
    }
}
