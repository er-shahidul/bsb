<?php

namespace PersonnelBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ExCareerInformationType extends CareerInformationType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\ExCareerInformation'
        ));
    }
}
