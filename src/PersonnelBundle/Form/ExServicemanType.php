<?php

namespace PersonnelBundle\Form;

use Doctrine\ORM\EntityRepository;
use PersonnelBundle\Entity\Property;
use PersonnelBundle\Entity\ExServiceman;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ExServicemanType extends ServingPersonnelType
{
    use RankCorpModifierTrait;

    private $office;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->office = $options['office'];

        parent::buildForm($builder, $options);

        $builder
            ->add('identityNumber', TextType::class, [
                'required' => TRUE,
                'label' => 'ব্যক্তিগত নম্বর',
                'attr'   => array(
                    'placeholder' => 'ব্যক্তিগত নম্বর'
                ),
            ])
            ->add('reEmployment', ChoiceType::class, [
                'label' => 'পরবর্তী নিয়োগে ইচ্ছুক',
                'choices' => array(
                    'No' => 'No',
                    'Yes' => 'Yes',
                ),
                'multiple'=>false,
            ])
            ->add('deceased', ChoiceType::class, [
                'label' => 'মৃত/জীবিত',
                'choices' => array(
                    'মৃত' => '1',
                    'জীবিত' => '0',
                ),
                'multiple'=>false,
                'expanded'=>true
            ])
            ->add('shantiNebas', ChoiceType::class, [
                'label' => 'শান্তিনিবাস বসবাস',
                'choices' => array(
                    'হাঁ' => '1',
                    'না' => '0',
                ),
                'multiple'=>false,
                'expanded'=>true
            ])
            ->add('afterRetirementCityOrVillage', ChoiceType::class, [
                'label' => 'অবসর গ্রহণের পর বসবাসের প্রকৃতি',
                'choices' => array(
                    'শহর' => '1',
                    'গ্রাম' => '0',
                ),
                'multiple'=>false,
                'expanded'=>true
            ])
            ->add('afterRetirementLivingNature', ChoiceType::class, array(
                'label' => 'অবাবসনের ধরণ',
                'choices' => Property::LIVING_NATURE,
            ))
            ->add('afterRetirementSourceOfIncome', ChoiceType::class, array(
                'label' => 'অবসর গ্রহণের পর আয়ের উৎস',
                'choices' => Property::INCOME_SOURCE,
            ))
            ->add('afterRetirementPlantingLand', TextType::class, [
                'required'    => false,
                'label' => 'আবাদযোগ্য জমির পরিমান (শতাংশ/বিঘা/একর)',
                'attr'   => array(
                    'placeholder' => 'আবাদযোগ্য জমির পরিমান'
                )
            ])
            ->add('inheritNID', TextType::class, [
                'required'    => false,
                'label' => 'উত্তরাধিকারীর জাতীয় পরিচয়পত্র নম্বর',
                'attr'   => array(
                    'placeholder' => 'উত্তরাধিকারীর জাতীয় পরিচয়পত্র নম্বর'
                )
            ])
            ->add('inheritBirthDate', NULL, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr'   => array(
                    'placeholder' => 'উত্তরাধিকারীর জন্ম তারিখ' ,
                    'class' => 'date-picker',
                    'data-date-end-date' => 'today'
                ),
                'label'  => 'উত্তরাধিকারীর জন্ম তারিখ'
            ))
            ->add('dispensary', EntityType::class, [
                'class'         => 'MedicalBundle\Entity\Dispensary',
                'label'         => 'ঔষধালয়',
                'choice_label' => 'name',
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('dispensary');
                    $qb = $qb->where('dispensary.office = :office')
                        ->setParameter('office', $this->office);

                    return $qb;
                },
                'placeholder' => 'ঔষধালয়',
            ])
            ->add('unMission', ChoiceType::class, [
                'label' => 'বৈদেশিক মিশন',
                'choices' => array(
                    'হাঁ' => '1',
                    'না' => '0',
                ),
                'multiple'=>false,
                'expanded'=>true
            ])
            ->add('disabilityReason', TextAreaType::class, array(
                'required'    => false,
                'attr'   => array(
                    'placeholder' => 'অক্ষমতার কারণ'
                ),
                'label'  => 'অক্ষমতার কারণ'
            ))
            ->add('deceasedReason', TextAreaType::class, array(
                'required'    => false,
                'label'  => 'মৃত্যুর কারন',
                'attr'   => array(
                    'placeholder' => 'মৃত্যুর কারন'
                )
            ))
            ->add('deceasedPlace', TextType::class, [
                'required'    => false,
                'label' => 'মৃত্যু স্থান',
                'attr'   => array(
                    'placeholder' => 'মৃত্যু স্থান'
                )
            ])
            ->add('inheritName', TextType::class, [
                'required'    => false,
                'label' => 'উত্তরাধিকার নাম',
                'attr'   => array(
                    'placeholder' => 'উত্তরাধিকার নাম'
                )
            ])
            ->add('inheritRelation', TextType::class, [
                'required'    => false,
                'label' => 'উত্তরাধিকার সম্পর্ক',
                'attr'   => array(
                    'placeholder' => 'উত্তরাধিকার সম্পর্ক'
                )
            ])
            ->add('freedomFighter', ChoiceType::class, [
                'label' => 'মুক্তিযোদ্ধা',
                'choices' => array(
                    'হাঁ' => '1',
                    'না' => '0',
                ),
                'multiple'=>false,
                'expanded'=>true
            ])
            ->add('deceasedDate', NULL, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr'   => array(
                    'placeholder' => 'মৃত্যু তারিখ' ,
                    'class' => 'date-picker',
                    'data-date-end-date' => 'today'
                ),
                'label'  => 'মৃত্যু তারিখ'
            ))
            ->add('reservistLastDate', NULL, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr'   => array(
                    'class' => 'date-picker',
                    'placeholder' => 'সংরক্ষণের সময়',
                ),
                'label'  => 'সংরক্ষণের সময়'
            ))
            ->add('remarks', NULL, [
                'label' => 'মন্তব্য (কম্পিউটার/ড্রাইভিং দক্ষতা/অ-সামরিক লাইসেন্স/অন্যান্য)',
                'attr'   => array(
                    'placeholder' => 'মন্তব্য',
                ),
            ])
            ->add('emergencyName', NULL, [
                'label' => 'নাম ',
                'attr'   => array(
                    'placeholder' => 'নাম ',
                ),
            ])
            ->add('emergencyRelation', NULL, [
                'label' => 'সম্পর্ক ',
                'attr'   => array(
                    'placeholder' => 'সম্পর্ক ',
                ),
            ])
            ->add('emergencyNumber', NULL, [
                'label' => 'যোগাযোগের নম্বর ',
                'attr'   => array(
                    'placeholder' => 'যোগাযোগের নম্বর ',
                ),
            ])
            ->add('emergencyAddress', NULL, [
                'label' => 'ঠিকানা ',
                'attr'   => array(
                    'placeholder' => 'ঠিকানা ',
                ),
            ])
            ->add('trade', ChoiceType::class, array(
                'label' => 'ট্রেড',
                'choices' => Property::TRADE_CHOICES,
            ))
            ->add('disciplineStatus', ChoiceType::class, array(
                'label' => 'শৃঙ্খলাবোধ',
                'placeholder' => 'শৃঙ্খলাবোধ',
                'choices'  => array(
                    'Exemplary' => Property::EXEMPLARY,
                    'Good' => Property::GOOD,
                    'Satisfactory' => Property::SATISFACTORY,
                    'Unsatisfactory' => Property::UNSATISFACTORY
                )
            ))
            ->add('retirementReason', NULL, [
                'required'    => TRUE,
                'placeholder' => 'অবসরের কারণ',
                'label' => 'অবসরের কারণ'
            ])
            ->add('retirementDate', NULL, array(
                'required'    => TRUE,
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr'   => array(
                    'class' => 'date-picker',
                    'placeholder' => 'অবসরের তারিখ',
                ),
                'label'  => 'অবসরের তারিখ'
            ));
        $builder
            ->add('tsNumber', TextType::class, [
                'label' => 'টি এস নম্বর',
                'attr'   => array(
                    'placeholder' => 'টি এস নম্বর'
                )
            ])
            ->add('pensionRate', NULL, [
                'label' => 'পেনশন হার (যদি থাকে)',
                'attr' => array(
                    'placeholder' => 'পেনশন হার (যদি থাকে)'
                )
            ])
            ->add('office', EntityType::class, [
                'class'         => 'AppBundle\Entity\Office',
                'label'         => 'ডি এ এস বি',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('office');
                    $qb = $qb->join('office.officeType', 't')
                        ->where('t.name = :type')
                        ->setParameter('type', 'DASB');

                    return $qb;
                },
            ])
            ->add('additionalInfo', ExAdditionalInformationType::class)

            ;
        $this
            ->addCollectionForm($builder, 'educations', ExEducationalInformationType::class, '' )
            ->addCollectionForm($builder, 'trainings', ExTrainingInformationType::class, 'প্রশিক্ষণ তথ্য' )
            ->addCollectionForm($builder, 'careers', ExCareerInformationType::class, 'ক্যারিয়ারের তথ্য' )
            ->addCollectionForm($builder, 'families', ExFamilyInformationType::class, '' )
            ->addCollectionForm($builder, 'specialDiseases', ExSpecialDiseaseType::class, '' )
            ->addCollectionForm($builder, 'servicesInfo', ExServiceInformationType::class, 'চাকরীর তথ্য' )
        ;
        $this->addCollectionForm($builder, 'receivedFunds', FundReceivedType::class, 'তহবিল সংগ্রহ' );
        $this->modifyRankAndCorp($builder);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\ExServiceman',
            'office' => null
        ));
    }
}
