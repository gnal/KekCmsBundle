<?php

namespace Msi\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EmailController extends Controller
{
    /**
     * @Route("/email/{email}/test")
     */
    public function testAction()
    {
        $email = $this->getDoctrine()->getRepository('MsiCmsBundle:Email')->find($this->getRequest()->attributes->get('email'));

        if (!$email) {
            throw $this->createNotFoundException();
        }

        $this->get('msi_cms.mailer')->sendEmail($email->getName(), null, $this->getUser()->getEmail());

        return $this->redirect($this->generateUrl('msi_cms_email_admin_index'));
    }
}
