<?php

namespace PersonnelBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ServingEmploymentInformationType extends EmploymentInformationType
{

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\EmploymentInformation'
        ));
    }
}
