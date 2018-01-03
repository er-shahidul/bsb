<?php

namespace PersonnelBundle\Form;

use PersonnelBundle\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ServingPersonnelType extends AbstractType
{
    use UpazilaModifierTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'নাম',
                'attr' => [
                    'placeholder' => 'নাম'
                ]
            ])
            ->add('dateOfWedding', NULL, array(
                'required' => false,
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr'   => array(
                    'data-date-end-date' => '-10y',
                    'class' => 'date-picker',
                    'placeholder' => 'বিবাহের তারিখ'
                ),
                'label'  => 'বিবাহের তারিখ'
            ))
            ->add('gender', ChoiceType::class, [
                'choices' => array(
                        'পুরুষ' => 'Male',
                        'মহিলা' => 'Female',
                ),
                'data'    => 'Male',
                'multiple'=>false,
                'required' => true,
                'expanded'=>true
            ])
            ->add('bloodGroup', NULL, [
                'label'=> 'রক্তের গ্রূপ',
                'placeholder' => 'রক্তের গ্রূপ',
                'required' => TRUE])
            ->add('mobileNumber', NULL, [
                'label'  => 'মোবাইল নম্বর',
                'required'=> TRUE,
                'attr' => array(
                    'placeholder' => 'মোবাইল নম্বর'
                ),
            ])
            ->add('dateOfBirth', NULL, array(
                'widget' => 'single_text',
                'required'=> TRUE,
                'format' => 'y-M-d',
                'attr'   => array(
                    'class' => 'date-picker',
                    'data-date-end-date' => '-18y',
                    'placeholder' => 'জন্ম তারিখ'
                ),
                'label'  => 'জন্ম তারিখ'
            ))
            ->add('religion', NULL, [
                'placeholder' => 'ধর্ম',
                'required' => TRUE,
                'label' => 'ধর্ম'
            ])
            ->add('nationality', NULL, [
                'label' => 'জাতীয়তা'
            ])
            ->add('permanentDistrict', NULL, [
                'label' => 'জেলা',
                'required' => TRUE,
                'placeholder' => 'জেলা',
                'attr'  => [
                    'class'        => 'district',
                    'data-upazila' => 'permanent'
                ]
            ])->add('permanentUpazila', EntityType::class, [
                'class'   => 'PersonnelBundle\Entity\Upazila',
                'choices' => array()
            ])
            ->add('permanentVillage', TextType::class, [
                'label' => 'গ্রাম/মহল্লা/সড়ক',
                'attr' => array(
                    'placeholder' => 'গ্রাম/মহল্লা/সড়ক',
                ),
            ])
            ->add('permanentPostOffice', TextType::class, [
                'label' => 'পোষ্ট অফিস',
                'attr' => array(
                    'placeholder' => 'পোষ্ট অফিস',
                ),
            ])
            ->add('permanentPostCode', TextType::class, [
                'label' => 'পোষ্ট কোড',
                'attr' => array(
                    'placeholder' => 'পোষ্ট কোড',
                )
            ])
            ->add('district', NULL, [
                'label' => 'জেলা',
                'required' => TRUE,
                'placeholder' => 'জেলা',
                'attr'  => [
                    'class'        => 'district',
                    'data-upazila' => 'present'
                ]
            ])->add('upazila', EntityType::class, [
                'class'   => 'PersonnelBundle\Entity\Upazila',
                'choices' => array()
            ])
            ->add('village', TextType::class, [
                'label' => 'গ্রাম/মহল্লা/সড়ক',
                'attr' => array(
                    'placeholder' => 'গ্রাম/মহল্লা/সড়ক',
                ),
            ])
            ->add('postOffice', TextType::class, [
                'label' => 'পোষ্ট অফিস',
                'attr' => array(
                    'placeholder' => 'পোষ্ট অফিস',
                ),
            ])
            ->add('postCode', TextType::class, [
                'label' => 'পোষ্ট কোড',
                'attr' => array(
                    'placeholder' => 'পোষ্ট কোড',
                )
            ])
            ->add('identificationMark', NULL, [
                'label' => 'সনাক্তকারী চিহ্ন',
                'attr' => array(
                    'placeholder' => 'সনাক্তকারী চিহ্ন'
                )
            ])
            ->add('telephoneNumber', NULL, [
                'label' => 'টেলিফোন নম্বর',
                'attr' => array(
                    'placeholder' => 'টেলিফোন নম্বর'
                )
            ])
            ->add('email', EmailType::class, [
                'required'=> FALSE,
                'label' => 'ইমেইল',
                'attr' => array(
                    'placeholder' => 'ইমেইল'
                )
            ])
            ->add('nid', TextType::class, [
                'label' => 'জাতীয় পরিচয় পত্রের নম্বর',
                'required'=> FALSE,
                'attr' => array(
                    'placeholder' => 'জাতীয় পরিচয় পত্রের নম্বর'
                )
            ])
            ->add('height', NULL, [
                'label' => 'উচ্চতা',
                'attr' => array(
                    'placeholder' => 'উচ্চতা'
                )
            ])
//            ->add('chestMeasurement', NULL, [
//                'label' => 'বুকে পরিমাপ',
//                'attr' => array(
//                    'placeholder' => 'বুকে পরিমাপ'
//                )
//            ])
            ->add('photoFile', FileType::class, array(
                'required' => FALSE,
                'url_property' => 'webPhotoPath',
                'attr'     => array(
                    'accept' => "image/*"
                )
            ))
            ->add('designation', NULL, [
                'label' => 'পদবি',
                'attr' => [
                    'placeholder' => 'পদবি'
                ]
            ])
//            ->add('servingType', NULL, [
//                'label' => 'সেবা টাইপ',
//                'required'    => false,
//                'placeholder' => 'সেবা টাইপ'
//            ])
            ->add('office', EntityType::class, [
                'class'         => 'AppBundle\Entity\Office',
                'required' => TRUE,
                'label'         => 'অফিস',
                'placeholder' => 'অফিস'
            ])
            ->add('joiningDate', NULL, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr'   => array(
                    'class' => 'date-picker',
                    'placeholder' => 'ভর্তির তারিখ'
                ),
                'label'  => 'ভর্তির তারিখ'
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
            ->add('lastServedUnit', NULL, [
                'label' => 'শেষ কর্তব্বরত ইউনিট',
                'attr' => [
                    'placeholder' => 'শেষ কর্তব্বরত ইউনিট'
                ]
            ])
            ->add('remarks', NULL, [
                'label' => 'মন্তব্য',
                'attr' => [
                    'placeholder' => 'শমন্তব্য'
                ]
            ])
            ->add('joiningPlace', NULL, [
                'label' => 'যোগদান স্থান',
                'attr' => [
                    'placeholder' => 'যোগদান স্থান'
                ]
            ])
            ->add('identityNumber', TextType::class, [
                'required' => TRUE,
                'label' => 'ব্যক্তিগত নম্বর',
                'attr'   => array(
                    'placeholder' => 'ব্যক্তিগত নম্বর'
                ),
            ])
            ->add('submit', SubmitType::class, array(
                'attr' => array('class' => 'btn green col-md-12')
            ))

            ->add('fathersName', NULL, [
                'label' => 'পিতার নাম',
                'attr' => array(
                    'placeholder' => 'পিতার নাম',
                ),
            ])
            ->add('mothersName', NULL, [
                'label' => 'মাতার নাম',
                'attr' => array(
                    'placeholder' => 'মাতার নাম',
                ),
            ])
            ->add('fathersOccupation', NULL, [
                'label' => 'পেশা',
                'attr' => array(
                    'placeholder' => 'পেশা',
                ),
            ])
            ->add('mothersOccupation', NULL, [
                'label' => 'পেশা',
                'attr' => array(
                    'placeholder' => 'পেশা',
                ),
            ])
            ->add('fathersMobileNumber', NULL, [
                'label' => 'যোগাযোগের নম্বর',
                'attr' => array(
                    'placeholder' => 'যোগাযোগের নম্বর',
                ),
            ])
            ->add('mothersMobileNumber', NULL, [
                'label' => 'যোগাযোগের নম্বর',
                'attr' => array(
                    'placeholder' => 'যোগাযোগের নম্বর',
                ),
            ])
            ->add('fathersNokPercentage', NULL, [
                'label' => 'এনওকে%',
                'attr' => array(
                    'placeholder' => 'এনওকে%',
                ),
            ])
            ->add('mothersNokPercentage', NULL, [
                'label' => 'এনওকে%',
                'attr' => array(
                    'placeholder' => 'এনওকে%',
                ),
            ])
            ->add('fathersAddress', NULL, [
                'label' => 'ঠিকানা',
                'attr' => array(
                    'placeholder' => 'ঠিকানা',
                ),
            ])
            ->add('mothersAddress', NULL, [
                'label' => 'ঠিকানা',
                'attr' => array(
                    'placeholder' => 'ঠিকানা',
                ),
            ])
        ;

        $this
            ->addCollectionForm($builder, 'educations', EducationalInformationType::class, '' )
            ->addCollectionForm($builder, 'trainings', TrainingInformationType::class, 'প্রশিক্ষণ তথ্য' )
            ->addCollectionForm($builder, 'careers', CareerInformationType::class, 'ক্যারিয়ারের তথ্য' )
            ->addCollectionForm($builder, 'families', FamilyInformationType::class, '' )
            ->addCollectionForm($builder, 'servicesInfo', ServiceInformationType::class, 'চাকরীর তথ্য' )
        ;

        $this->modifyUpazilaElements($builder);
    }

    protected function addCollectionForm(FormBuilderInterface $builder, $name, $type, $label)
    {
        $builder->add($name, CollectionType::class, [
            'label'        => $label,
            'allow_add'    => TRUE,
            'delete_empty' => TRUE,
            'allow_delete' => TRUE,
            'entry_type'   => $type,
            'by_reference' => FALSE
        ]);

        return $this;
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

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'personnel_form';
    }
}
