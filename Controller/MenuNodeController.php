<?php

namespace Msi\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;

class MenuNodeController extends Controller
{
    /**
     * @Route("/admin/menu-node/sortz")
     */
    public function sortAction()
    {
        $id = $this->getRequest()->query->get('id');
        $node = $this->get('msi_cms_menu_node_admin')->getRepository()->find($this->getRequest()->query->get('id'));

        foreach ($this->getRequest()->query->get('array1') as $k => $v) {
            if ($v == $id) {
                $start = $k;
            }
        }

        foreach ($this->getRequest()->query->get('array2') as $k => $v) {
            if ($v == $id) {
                $end = $k;
            }
        }

        $number = $start - $end;

        if ($number > 0) {
            $this->get('msi_cms_menu_node_admin')->getRepository()->moveUp($node, abs($number));
        } elseif ($number < 0) {
            $this->get('msi_cms_menu_node_admin')->getRepository()->moveDown($node, abs($number));
        }

        // return $this->redirect($this->get('msi_cms_menu_node_admin')->generateUrl('index'));
        return new Response();
    }
}
