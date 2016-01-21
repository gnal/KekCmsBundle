<?php

namespace Msi\CmsBundle\EventListener;

use JMS\DiExtraBundle\Annotation as DI;
use Msi\AdminBundle\Event\UploadEntityEvent;
use Msi\AdminBundle\MsiAdminEvents;
use Msi\AdminBundle\Tools\Cutter2;
use Kek\UtilBundle\Event\EntityEvent;

/**
 * @DI\Service
 */
class ImageListener
{
    protected $uploader;

    /**
     * @DI\InjectParams({
     *     "uploader" = @DI\Inject("msi_admin.uploader")
     * })
     */
    public function __construct($uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @DI\Observe(MsiAdminEvents::ENTITY_POST_UPLOAD)
     */
    public function onEntityPostUpload(UploadEntityEvent $event)
    {
        if (!is_a($event->getEntity(), 'Msi\CmsBundle\Entity\Image') || $event->getField() !== 'image') {
            return;
        }

        $cutter = new Cutter2($event->getFile());
        $cutter->resize(100, 100);
        $cutter->save('t');
    }

    /**
     * @DI\Observe(MsiAdminEvents::ENTITY_PRE_UPLOAD)
     */
    public function onEntityPreUpload(UploadEntityEvent $event)
    {
        $entity = $event->getEntity();
        $field = $event->getField();
        if (is_a($entity, 'Msi\CmsBundle\Entity\Image') && $field === 'image') {
            $name = $entity->getName().'.'.$event->getFile()->guessExtension();
            $setFilenameMethod = 'set'.ucfirst($field);
            $entity->$setFilenameMethod($name);
        }
    }

    /**
     * @DI\Observe("pre_flush.image.update")
     */
    public function onPreFlushImageUpdate(EntityEvent $event)
    {
        $dir = $this->uploader->getUploadDir($event->getEntity(), 'image');
        $file = $event->getEntity()->getImage();
        $ext = substr($file, strpos($file, '.'));
        $newFile = $event->getEntity()->getName().$ext;
        rename($dir.'/'.$file, $dir.'/'.$newFile);
        $event->getEntity()->setImage($newFile);
    }
}
