<?php

namespace PersonnelBundle\Form;

use  Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExSpecialDiseaseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', NULL, [
                'label' => 'রোগের নাম',
                'attr' => array(
                    'placeholder' => 'রোগের নাম',
                ),
            ])
            ->add('provingBy', NULL, [
                'label' => 'রোগ নিরুপনকারী কর্তৃপক্ষ',
                'attr' => array(
                    'placeholder' => 'রোগ নিরুপনকারী কর্তৃপক্ষ',
                ),
            ])
            ->add('treatment', ChoiceType::class, [
                'label' => 'চিকিৎসা',
                'choices' => array(
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'multiple'=>false,
            ])
            ->add('remark', NULL, [
                'label' => 'মন্তব্য',
                'attr' => array(
                    'placeholder' => 'মন্তব্য',
                ),
            ])
            ->add('affectedDate', null, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr' => array(
                    'class' => 'date-picker',
                    'data-date-end-date' => "today",
                     'placeholder' => '',
                ),
                'label' => 'From'
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\ExSpecialDisease'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'specialdiseases';
    }


}
