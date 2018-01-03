<?php

namespace AccountBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FundHeadForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'required' => false,
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('sort', null, array(
                'required' => false,
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('fundType', null, array(
                'required' => false,
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('officeType', null, array(
                'required' => false,
                'constraints' => array(
                    new NotBlank()
                )
            ));;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\FundHead',
            'translation_domain' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'accountbundle_fundhead';
    }


}
