<?php

namespace PersonnelBundle\Form;

use  Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmploymentInformationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('unit', NULL, [
                'label' => 'ইউনিট',
                'attr' => array(
                    'placeholder' => 'ইউনিট',
                ),
            ])
            ->add('class', NULL, [
                'label' => 'শাখা',
                'attr' => array(
                    'placeholder' => 'শাখা',
                ),
            ])
            ->add('fromDate', null, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr' => array(
                    'class' => 'date-picker',
                    'data-date-end-date' => "today",
                     'placeholder' => '',
                ),
                'label' => 'From'
            ))
            ->add('toDate', null, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr' => array(
                    'class' => 'date-picker',
                    'data-date-end-date' => "today",
                    'placeholder' => '',
                ),
                'label' => 'To'
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\EmploymentInformation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'employmentinformation';
    }


}
