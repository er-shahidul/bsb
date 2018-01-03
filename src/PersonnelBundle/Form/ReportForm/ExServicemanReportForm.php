<?php

namespace PersonnelBundle\Form\ReportForm;

use PersonnelBundle\Entity\Property;
use PersonnelBundle\Form\RankCorpModifierTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExServicemanReportForm extends AbstractType
{
    use RankCorpModifierTrait;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('permanentDistrict', EntityType::class, [
                'class'   => 'PersonnelBundle\Entity\District',
                'label' => 'District',
                'placeholder' => 'District',
                'required' => false,
                'attr'  => [
                    'class'        => 'district bs-select',
                    'data-upazila' => 'permanentSearch',
                    'data-live-search' => 'true',
                    'data-size' => '8'
                ]
            ])
            ->add('permanentUpazila', EntityType::class, [
                'class'   => 'PersonnelBundle\Entity\Upazila',
                'label' => 'Upazila',
                'required' => false,
                'attr'  => [
                    'data-live-search' => 'true',
                    'data-size' => '8'
                ]
            ])
            ->add('office', EntityType::class, [
                'class'         => 'AppBundle\Entity\Office',
                'required' => false,
                'label'         => 'Office',
                'placeholder' => 'Select Office',
                'attr' => [
                    'class' => 'bs-select',
                    'data-live-search' => 'true',
                    'data-size' => '8'
                ],
            ])
            ->add('religion', EntityType::class, [
                'class' => 'PersonnelBundle\Entity\Religion',
                'required' => false,
                'attr' => [
                    'class' => 'bs-select',
                    'data-live-search' => 'true',
                    'data-size' => '8'
                ],
                'placeholder' => 'Select Religion.'
            ])
            ->add('gender', EntityType::class, [
                'class' => 'PersonnelBundle\Entity\Gender',
                'required' => false,
                'placeholder' => 'Select Gender'
            ])
            ->add('identityNumber', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'typeahead',
                    'placeholder' => 'Personal No',

                ],
                'label'         => 'Personal No',
            ])
            ->add('reEmployment', ChoiceType::class, [
                'label' => 'পরবর্তী নিয়োগে ইচ্ছুক',
                'placeholder' => 'Select Employment Option',
                'choices' => array(
                    'No' => 'No',
                    'Yes' => 'Yes',
                ),
                'multiple'=>false,
            ])
            ->add('trade', ChoiceType::class, array(
                'label' => 'Trade',
                'placeholder' => 'Select Trade',
                'choices' => Property::TRADE_CHOICES,
            ))
            ->add('tsNumber', TextType::class, [
                'required'    => false,
                'label' => 'Ts No',
            ])
            ->add('freedomFighter', ChoiceType::class, [
                'placeholder' => 'Freedom Fighter Or Not.',
                'choices'  => array(
                    'YES' => '1',
                    'NO' => '0',
                )
            ])
            ->add('exFromDate', TextType::class, [
                'label' => 'Retirement Date From',
                'attr' => array(
                    'class' => 'date-picker',
                    'data-date-end-date' => "today"
                ),
            ])
            ->add('exToDate', TextType::class, [
                'label' => 'Retirement Date To',
                'attr' => array(
                    'class' => 'date-picker',
                    'data-date-end-date' => "today"
                ),
            ])
            ->add('deceasedFromDate', TextType::class, [
                'attr' => array(
                    'class' => 'date-picker',
                    'data-date-end-date' => "today"
                ),
            ])
            ->add('deceasedToDate', TextType::class, [
                'attr' => array(
                    'class' => 'date-picker',
                    'data-date-end-date' => "today"
                ),
            ])
        ;
        $this->modifyRankAndCorp($builder);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'search';
    }
}
