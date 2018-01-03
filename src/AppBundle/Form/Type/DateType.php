<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'html5' => false,
            'attr' => [
                'class' => 'date-picker input-small',
            ],
            'format' => 'yyyy-MM-dd'
        ));
    }

    public function getParent()
    {
        return DateTimeType::class;
    }
}
