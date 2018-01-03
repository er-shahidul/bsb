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

class RCELApplicationForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $extraData = $options['extraData'];

        $builder
            ->add('agreement', CheckboxType::class, array(
                'label'    => ' ',
                'mapped' => false,
                'required' => false,
                'constraints' => new IsTrue(['message' => ''])
            ))
            ->add('isServiceManDead', CheckboxType::class, array(
                'label'    => ' ',
                'mapped' => false,
                'required' => false,
                'data' => (isset($extraData['isServiceManDead'])) ? true : false
            ))
            ->add('isApplicantServiceManSpouse', CheckboxType::class, array(
                'label'    => ' ',
                'mapped' => false,
                'required' => false,
                'data' => (isset($extraData['isApplicantServiceManSpouse'])) ? true : false
            ))
            ->add('isGettingPensionAsSpouse', CheckboxType::class, array(
                'label'    => ' ',
                'mapped' => false,
                'required' => false,
                'data' => (isset($extraData['isGettingPensionAsSpouse'])) ? true : false
            ))
            ->add('isSpouseUnmarried', CheckboxType::class, array(
                'label'    => ' ',
                'mapped' => false,
                'required' => false,
                'data' => (isset($extraData['isSpouseUnmarried'])) ? true : false
            ))
            ->add('isReallyHelpless', CheckboxType::class, array(
                'label'    => ' ',
                'mapped' => false,
                'required' => false,
                'data' => (isset($extraData['isReallyHelpless'])) ? true : false
            ))
            ->add('isRCELGrantEligible', CheckboxType::class, array(
                'label'    => ' ',
                'mapped' => false,
                'required' => false,
                'data' => (isset($extraData['isRCELGrantEligible'])) ? true : false
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WelfareBundle\Entity\RCELApplication',
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
