<?php

namespace PersonnelBundle\Form;

use PersonnelBundle\Entity\Property;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ServingMilitaryType extends ServingPersonnelType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        parent::buildForm($builder, $options);
        $builder
            ->add('trade', ChoiceType::class, array(
                'label' => 'ট্রেড',
                'choices' => Property::TRADE_CHOICES,
            ))
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
            ->add('unMission', ChoiceType::class, [
                'label' => 'বৈদেশিক মিশন',
                'choices' => array(
                    'হাঁ' => '1',
                    'না' => '0',
                ),
                'multiple'=>false,
                'expanded'=>true
            ])
            ->add('serviceCondition', TextType::class, [
                'label' => 'চাকুরীর শর্তাবলী ১/১৩',
                'required'=> FALSE,
                'attr' => [
                    'placeholder' => 'চাকুরীর শর্তাবলী ১/১৩',
                ]
            ])
            ->add('disciplineInformation', NULL, [
                'label' => 'শৃঙ্খলা বিবরণ',
                'attr' => [
                    'placeholder' => 'শৃঙ্খলা বিবরণ'
                ]
            ])
            ->add('medicalClassification', NULL, [
                'label' => 'ডাক্তারী শ্রেণীবিন্যাস',
                'attr' => [
                    'placeholder' => 'ডাক্তারী শ্রেণীবিন্যাস',
                ]
            ])
            ->add('militaryProfile', MilitaryProfileType::class)
            ;
        $this->addCollectionForm($builder, 'employmentInformations', EmploymentInformationType::class, '' );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\ServingMilitary'
        ));
    }
}
