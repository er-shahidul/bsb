<?php

namespace AccountBundle\Form;

use AppBundle\Form\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentVoucherForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('toOrFrom');
        $builder->add('against');
        $builder->add('account', null, [
            'choice_label' => 'name'
        ]);
        $builder->add('chequeDate', DateType::class);
        $builder->add('chequeNumber');
        $builder->add('voucherDetails', CollectionType::class, [
            'entry_type' => PaymentVoucherDetailForm::class,
            'allow_delete' => true,
            'allow_add' => true,
            'prototype' => true
        ]);
        $builder->add('description');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\Voucher',
            'translation_domain' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'accountbundle_voucher';
    }
}