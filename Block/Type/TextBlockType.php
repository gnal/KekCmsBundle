<?php

namespace Msi\CmsBundle\Block\Type;

use Msi\CmsBundle\Block\Type\BaseBlockType;
use Msi\CmsBundle\Model\Block;
use Msi\CmsBundle\Model\Page;
use Symfony\Component\Form\FormBuilder;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("msi_cms.block.type.text")
 */
class TextBlockType extends BaseBlockType
{
    public function execute(Block $block, Page $page)
    {
        return $block->getTranslation()->getSettings()['body'];
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
        $builder->add('body', 'textarea', [
            'attr' => [
                'class' => 'tinymce',
            ],
        ]);
    }

    public function getName()
    {
        return 'text';
    }
}
