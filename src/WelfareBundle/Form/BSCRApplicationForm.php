<?php

namespace WelfareBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use PersonnelBundle\Entity\ExServiceman;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Twig\Test\NodeTestCase;

class BSCRApplicationForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $extraData = $options['extraData'];

        $builder
            ->add('fathersName', null, [
                'mapped' => false,
                'data' => (isset($extraData['fathersName'])) ? $extraData['fathersName'] : '',
            ])
            ->add('fathersAge', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['fathersAge'])) ? $extraData['fathersAge'] : ''
            ])
            ->add('fathersQualified', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['fathersQualified'])) ? $extraData['fathersQualified'] : ''
            ])

            ->add('mothersName', null, [
                'mapped' => false,
                'data' => (isset($extraData['mothersName'])) ? $extraData['mothersName'] : ''
            ])
            ->add('mothersAge', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['mothersAge'])) ? $extraData['mothersAge'] : ''
            ])
            ->add('mothersQualified', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['mothersQualified'])) ? $extraData['mothersQualified'] : ''
            ])

            ->add('spouseName', null, [
                'mapped' => false,
                'data' => (isset($extraData['spouseName'])) ? $extraData['spouseName'] : ''
            ])
            ->add('spouseAge', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['spouseAge'])) ? $extraData['spouseAge'] : ''
            ])
            ->add('spouseQualified', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['spouseQualified'])) ? $extraData['spouseQualified'] : ''
            ])

            ->add('child1', null, [
                'mapped' => false,
                'data' => (isset($extraData['child1'])) ? $extraData['child1'] : ''
            ])
            ->add('child1Age', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['child1Age'])) ? $extraData['child1Age'] : ''
            ])
            ->add('child1Qualified', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['fathersName'])) ? $extraData['fathersName'] : ''
            ])

            ->add('child2', null, [
                'mapped' => false,
                'data' => (isset($extraData['child2'])) ? $extraData['child2'] : ''
            ])
            ->add('child2Age', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['child2Age'])) ? $extraData['child2Age'] : ''
            ])
            ->add('child2Qualified', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['child2Qualified'])) ? $extraData['child2Qualified'] : ''
            ])

            ->add('child3', null, [
                'mapped' => false,
                'data' => (isset($extraData['child3'])) ? $extraData['child3'] : ''
            ])
            ->add('child3Age', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['child3Age'])) ? $extraData['child3Age'] : ''
            ])
            ->add('child3Qualified', null, [
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
                'data' => (isset($extraData['child3Qualified'])) ? $extraData['child3Qualified'] : ''
            ])

            ->add('howDoLiveWithoutIncomeSourceOrHelp', null, [
                'mapped' => false,
                'data' => (isset($extraData['howDoLiveWithoutIncomeSourceOrHelp'])) ? $extraData['howDoLiveWithoutIncomeSourceOrHelp'] : ''
            ])
            ->add('incomeFromAnySource', null, [
                'mapped' => false,
                'data' => (isset($extraData['incomeFromAnySource'])) ? $extraData['incomeFromAnySource'] : ''
            ])
            ->add('argumentToGetTheGrant', null, [
                'mapped' => false,
                'data' => (isset($extraData['argumentToGetTheGrant'])) ? $extraData['argumentToGetTheGrant'] : ''
            ])
            ->add('grantRecommendation', null, [
                'mapped' => false,
                'data' => (isset($extraData['grantRecommendation'])) ? $extraData['grantRecommendation'] : ''
            ])
            ->add('unionChairmanRemarks', null, [
                'mapped' => false,
                'data' => (isset($extraData['unionChairmanRemarks'])) ? $extraData['unionChairmanRemarks'] : ''
            ])
            ->add('agreement', CheckboxType::class, array(
                'label'    => ' ',
                'mapped' => false,
                'required' => false,
                'constraints' => new IsTrue(['message' => ''])
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WelfareBundle\Entity\BSCRApplication',
            'extraData' => []
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'extra';
    }
}
