<?php

namespace PersonnelBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use  Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExAdditionalInformationType extends AbstractType
{
    use InheritUpazilaModifierTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('inheritPermanentDistrict', NULL, [
                'label' => 'জেলা',
                'required' => false,
                'placeholder' => 'জেলা',
                'attr'  => [
                    'class'        => 'inheritDistrict',
                    'data-upazila' => 'inheritPermanent'
                ]
            ])->add('inheritPermanentUpazila', EntityType::class, [
                'class'   => 'PersonnelBundle\Entity\Upazila',
                'choices' => array(),
                'required' => false,
            ])
            ->add('inheritPermanentVillage', TextType::class, [
                'label' => 'গ্রাম/মহল্লা/সড়ক',
                'attr' => array(
                    'placeholder' => 'গ্রাম/মহল্লা/সড়ক',
                ),
                'required' => false,
            ])
            ->add('inheritPermanentPostOffice', TextType::class, [
                'label' => 'পোষ্ট অফিস',
                'attr' => array(
                    'placeholder' => 'পোষ্ট অফিস',
                ),
                'required' => false,
            ])
            ->add('inheritPermanentPostCode', TextType::class, [
                'label' => 'পোষ্ট কোড',
                'attr' => array(
                    'placeholder' => 'পোষ্ট কোড',
                ),
                'required' => false,
            ])
            ->add('inheritDistrict', NULL, [
                'label' => 'জেলা',
                'required' => false,
                'placeholder' => 'জেলা',
                'attr'  => [
                    'class'        => 'inheritDistrict',
                    'data-upazila' => 'inheritPresent'
                ]
            ])->add('inheritUpazila', EntityType::class, [
                'class'   => 'PersonnelBundle\Entity\Upazila',
                'choices' => array(),
                'required' => false,
            ])
            ->add('inheritVillage', TextType::class, [
                'label' => 'গ্রাম/মহল্লা/সড়ক',
                'attr' => array(
                    'placeholder' => 'গ্রাম/মহল্লা/সড়ক',
                ),
                'required' => false,
            ])
            ->add('inheritPostOffice', TextType::class, [
                'label' => 'পোষ্ট অফিস',
                'attr' => array(
                    'placeholder' => 'পোষ্ট অফিস',
                ),
                'required' => false,
            ])
            ->add('inheritPostCode', TextType::class, [
                'label' => 'পোষ্ট কোড',
                'attr' => array(
                    'placeholder' => 'পোষ্ট কোড',
                ),
                'required' => false,
            ])
            ->add('inheritGuardian', TextType::class, [
                'label' => ' পিতা/ স্বামীর  নাম',
                'attr' => array(
                    'placeholder' => '',
                ),
                'required' => false,
            ])
            ->add('inheritOccupation', TextType::class, [
                'label' => 'উত্তরাধিকারীর পেশা',
                'attr' => array(
                    'placeholder' => '',
                ),
                'required' => false,
            ])
            ->add('childrenInfo', TextType::class, [
                'label' => '  সন্তানাদির বিবরণ',
                'attr' => array(
                    'placeholder' => '',
                ),
                'required' => false,
            ])
            ->add('fixedOrCurrentAsset', ChoiceType::class, array(
                'label' => ' স্থাবর /অস্থাবর সম্পত্তি আছে কিনা',
                'choices' => array(
                    'হাঁ' => '1',
                    'না' => '0',
                ),
                'multiple'=>false,
                'expanded'=>true,
                'required' => false,
            ))
            ->add('assetInfo', TextType::class, [
                'label' => ' স্থাবর /অস্থাবর সম্পত্তির বিবরণ সহ পরিমাণ সহ উল্লেখ',
                'attr' => array(
                    'placeholder' => '',
                ),
                'required' => false,
            ])
            ->add('amountOfLand', TextType::class, [
                'label' => 'জমির পরিমাণ',
                'attr' => array(
                    'placeholder' => '',
                ),
                'required' => false,
            ])
            ->add('totalIncome', TextType::class, [
                'label' => ' মোট আয়ের পরিমাণ',
                'attr' => array(
                    'placeholder' => '',
                ),
                'required' => false,
            ])
            ->add('monthlyIncome', TextType::class, [
                'label' => ' মাসিক আয়  সম্পূর্ণ বৃত্তান্ত সহ',
                'attr' => array(
                    'placeholder' => '',
                ),
                'required' => false,
            ])
            ->add('pensionInfo', TextType::class, [
                'label' => ' পেনশন ভাতার পরিমাণ  (পেনশন ভুক্ত হলে ব্যাংক শাখার নাম, হিসাব নম্বর ও ঠিকানা',
                'attr' => array(
                    'placeholder' => '',
                ),
                'required' => false,
            ])
            ->add('loanInfo', TextType::class, [
                'label' => ' ঋণের নিমিত্তে ব্যবহৃত ব্যাংক শাখার নাম, হিসাব নম্বর ও ঠিকানা',
                'attr' => array(
                    'placeholder' => '',
                ),
                'required' => false,
            ])
            ->add('commutationInfo', TextType::class, [
                'label' => 'কম্যুটেশন কত টাকা পেয়েছে এবং কোমুটেশন টাকা দিয়ে কি করেছে',
                'attr' => array(
                    'placeholder' => '',
                ),
                'required' => false,
            ])
            ->add('missionIncome', TextType::class, [
                'label' => 'মিশনে কত টাকা পেয়েছে',
                'attr' => array(
                    'placeholder' => '',
                ),
                'required' => false,
            ])

        ;

        $this->modifyUpazilaElements($builder);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\ExAdditionalInformation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'additioanl_info';
    }


}
