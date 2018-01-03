<?php

namespace MedicalBundle\Form;

use Doctrine\ORM\EntityRepository;
use MedicalBundle\Repository\DispensaryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RequisitionForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('dispensary', ChoiceType::class, [
            'choices' => $options['dispensaryChoices'],
            'required' => true,
            'attr' => [
                'required' => 'required'
            ],
            'constraints' => [
                new NotBlank()
            ]
        ]);

        $builder->add('requisitionDetails', CollectionType::class, [
            'entry_type' => RequisitionDetailForm::class,
            'label' => false
        ]);

        $builder->get('dispensary')->addModelTransformer($options['dispensaryTransformer']);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MedicalBundle\Entity\Requisition',
            'dispensaryTransformer' => null,
            'dispensaryChoices' => [],
            'attr' => [
                'class' => 'jq-validate'
            ]
        ));
    }
}