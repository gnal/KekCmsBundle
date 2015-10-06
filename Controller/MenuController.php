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

        $menus = $qb->getQuery()->execute();

        if (!$menus) {
            throw $this->createNotFoundException();
        }

        return $this->render('MsiCmsBundle:Menu:show.html.twig', [
            'menus' => $menus,
        ]);
    }
}
