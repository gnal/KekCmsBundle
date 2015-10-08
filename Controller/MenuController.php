<?php

namespace Msi\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends Controller
{
    /**
     * @Route("/menu/{menu}/render")
     * @Template()
     */
    public function showAction(Request $request)
    {
        $qb = $this->getDoctrine()->getManager()->getRepository('MsiCmsBundle:Menu')->createQueryBuilder('a');

        $qb->join('a.parent', 'a_parent');
        $qb->andWhere($qb->expr()->eq('a_parent.uniqueName', ':a_parent_uniqueName'));
        $qb->setParameter('a_parent_uniqueName', $request->attributes->get('menu'));

        $qb->join('a.translations', 'a_translations');
        $qb->andWhere($qb->expr()->eq('a_translations.published', ':a_translations_published'));
        $qb->setParameter('a_translations_published', true);

        $menus = $qb->getQuery()->execute();

        if (!$menus) {
            throw $this->createNotFoundException();
        }

        return $this->render('MsiCmsBundle:Menu:show.html.twig', [
            'menus' => $menus,
        ]);
    }
}
