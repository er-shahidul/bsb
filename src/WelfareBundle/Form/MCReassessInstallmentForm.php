<?php

namespace WelfareBundle\Form;

use AppBundle\Form\Type\DateType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MCReassessInstallmentForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note', CKEditorType::class, [
                'required' => false,
                'config' => array('toolbar' => 'welfare_wysiwyg'),
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 10,
                    'cols' => 55
                ],
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WelfareBundle\Entity\MCReassessInstallment',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'welfarebundle_payment_receive';
    }
}
