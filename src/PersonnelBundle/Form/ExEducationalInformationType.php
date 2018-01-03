<?php

namespace PersonnelBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ExEducationalInformationType extends EducationalInformationType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\ExEducationalInformation'
        ));
    }
}
