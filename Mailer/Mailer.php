<?php

namespace Msi\CmsBundle\Mailer;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("msi_cms.mailer")
 */
class Mailer
{
    protected $container;

    /**
     * @DI\InjectParams({
     *     "container" = @DI\Inject("service_container")
     * })
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function sendEmail($name, $data = null, $toWho = null, $attachments = [])
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository($this->container->getParameter('msi_cms.email.class'));
        $emails = $repo->findAllPublishedByName($name);

        foreach ($emails as $email) {
            if ($data) {
                $body = preg_replace_callback(
                    '@#[a-zA-z0-9]+#@',
                    function ($matches) use ($data) {
                        $code = str_replace('#', '', $matches[0]);
                        $getter = 'get'.ucfirst($code);

                        return is_array($data) ? $data[$code] : $data->$getter();
                    },
                    $email->getTranslation()->getBody()
                );
            } else {
                $body = $email->getTranslation()->getBody();
            }

            $rendered = $this->container->get('templating')->render('MsiCmsBundle:Email:base.html.twig', [
                'subject' => $email->getTranslation()->getSubject(),
                'body' => $body,
            ]);

            // fix "to" to merge it with entity "to"

            if ($email->getToWho() && $toWho) {
                $toWho = ', '.$toWho;
            }

            // fix stuff to be array

            if ($email->getToWho().$toWho) {
                $to = explode(', ', $email->getToWho().$toWho);
            } else {
                $to = [];
            }

            if ($email->getCc()) {
                $cc = explode(', ', $email->getCc());
            } else {
                $cc = [];
            }

            if ($email->getBcc()) {
                $bcc = explode(', ', $email->getBcc());
            } else {
                $bcc = [];
            }

            // send email

            $this->send(
                $email->getFromWho(),
                $to,
                $cc,
                $bcc,
                $email->getTranslation()->getSubject(),
                $rendered,
                $attachments
            );
        }
    }

    protected function send($fromWho, $toWho, $cc, $bcc, $subject, $body, $attachments = [])
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromWho)
            ->setTo($toWho)
            ->setCc($cc)
            ->setBcc($bcc)
            ->setBody($body, 'text/html')
        ;

        foreach ($attachments as $attachment) {
            $message->attach(\Swift_Attachment::fromPath($attachment));
        }

        $this->container->get('mailer')->send($message);
    }
}
