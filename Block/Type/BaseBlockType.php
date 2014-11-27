<?php

namespace Msi\CmsBundle\Block\Type;

use Msi\CmsBundle\Model\Block;
use Msi\CmsBundle\Model\Page;
use Symfony\Component\Form\FormBuilder;

abstract class BaseBlockType
{
    // resolve block to return the content
    abstract public function execute(Block $block, Page $page);

    abstract public function getName();

    public function buildForm(FormBuilder $builder)
    {
    }

    public function buildTranslationForm(FormBuilder $builder)
    {
    }

    public function setDefaultTranslationOptions(OptionsResolverInterface $resolver)
    {
    }
}
