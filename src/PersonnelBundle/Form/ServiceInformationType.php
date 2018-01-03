<?php

namespace PersonnelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceInformationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('posting', NULL, ['label' => 'পোস্টিং/প্রোমোশন/ এ পি পি টি /মিশন/ এস টি এম কে'])
            ->add('date', NULL, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr'   => array(
                    'class' => 'date-picker'
                ),
                'label'  => 'Date'
            ))
            ->add('remarks', NULL, [
                'attr' => [
                    'placeholder' => 'মন্তব্য'
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\ServiceInformation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'serviceinformation';
    }
}
