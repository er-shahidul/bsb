<?php

namespace PersonnelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EducationalInformationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('degree', NULL, [
                'label' => 'সনদ',
                'attr' => array(
                    'placeholder' => 'সনদ',
                ),
            ])
            ->add('institution', NULL, [
                'label' => 'প্রতিষ্ঠান',
                'attr' => array(
                    'placeholder' => 'প্রতিষ্ঠান',
                ),
            ])
            ->add('grade', NULL, [
                'label' => 'গ্রেড',
                'attr' => array(
                    'placeholder' => 'গ্রেড',
                ),
            ])
            ->add('passingYear', NULL, [
                'label' => 'পাসের বছর',
                'attr' => array(
                    'placeholder' => 'পাসের বছর',
                ),
            ])
            ->add('educationType', ChoiceType::class, [
                'choices' => array(
                    'সামরিক' => 'Military',
                    'বেসামরিক' => 'Civilian',
                ),
                'label' => 'প্রকার',
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\EducationalInformation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'educationalinformation';
    }


}
