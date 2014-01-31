<?php

namespace Msi\AdminBundle\Grid\Column;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BooleanColumn extends BaseColumn
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'toggleable' => true,
            'class_true' => 'success',
            'class_false' => 'danger',
            'icon_true' => 'fa-check-circle',
            'icon_false' => 'fa-check-circle',
        ]);
    }
}
