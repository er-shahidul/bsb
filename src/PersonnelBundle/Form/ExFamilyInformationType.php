<?php

namespace PersonnelBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ExFamilyInformationType extends FamilyInformationType
{

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\ExFamilyInformation'
        ));
    }
}
