<?php

namespace PersonnelBundle\Form\ReportForm;

use Symfony\Component\Form\AbstractType;
use PersonnelBundle\Entity\ServingMilitary;
use PersonnelBundle\Form\RankCorpModifierTrait;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ServingMilitaryReportForm extends AbstractType
{
    use RankCorpModifierTrait;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('office', EntityType::class, [
                'class'         => 'AppBundle\Entity\Office',
                'label'         => 'Office',
                'placeholder' => 'Select Office.',
                'attr' => [
                    'class' => 'bs-select',
                    'data-live-search' => 'true',
                    'data-size' => '8'
                ]
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
