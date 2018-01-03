<?php

namespace PersonnelBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ExServiceInformationType extends ServiceInformationType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\ExServiceInformation'
        ));
    }
}
