<?php

namespace Msi\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class TinyMceController extends Controller
{
    /**
     * @Route("/{_locale}/admin/tinymce")
     * @Template()
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository($this->container->getParameter('msi_cms.image.class'));

        $qb = $repo->createQueryBuilder('a');

        $qb->addOrderBy('a.name', 'ASC');

        $images = $qb->getQuery()->execute();

        return [
            'images' => $images,
        ];
    }
}
