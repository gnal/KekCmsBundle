<?php

namespace Msi\CmsBundle\Grid\Column;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Msi\AdminBundle\Grid\AbstractColumn;

class BlockPagesColumn extends AbstractColumn
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'truncate' => true,
            'truncate_length' => 30,
            'truncate_preserve' => false,
            'truncate_separator' => '...',
            'template' => 'MsiCmsBundle:Column:BlockPagesColumn.html.twig',
        ]);
    }
}
