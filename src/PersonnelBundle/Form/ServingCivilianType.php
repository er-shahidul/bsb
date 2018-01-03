<?php

namespace PersonnelBundle\Form;

use PersonnelBundle\Entity\ServingPersonnel;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServingCivilianType extends ServingPersonnelType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('disciplineInformation', NULL, [
                'label' => 'শৃঙ্খলা বিবরণ',
                'attr' => [
                    'placeholder' => 'শৃঙ্খলা বিবরণ'
                ]
            ])
            ->add('aprGrading', ChoiceType::class, array(
                'label' => 'পূর্বের ০৩ বছরের এপিআর গ্রেডিং',
                'choices' => array(
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D'
                ),
                'multiple' => true,
                'required' => false
            ))
            ->add('dateOfRetirement', NULL, array(
                'required'    => false,
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr'   => array(
                    'class' => 'date-picker',
                    'placeholder' => 'অবসরের তারিখ',
                ),
                'label'  => 'অবসরের তারিখ'
            ))
            ->add('medicalClassification', NULL, [
                'label' => 'ডাক্তারী শ্রেণীবিন্যাস',
                'attr' => [
                    'placeholder' => 'ডাক্তারী শ্রেণীবিন্যাস',
                ]
            ]);
        $this->addCollectionForm($builder, 'employmentInformations', EmploymentInformationType::class, '' );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\ServingCivilian'
        ));
    }
}
