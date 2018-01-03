<?php

namespace WelfareBundle\Form;

use AppBundle\Form\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentReceiveForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('paymentAmount',NumberType::class, [
                'attr' => ['class' => 'amount'],
            ])
            ->add('date',DateType::class, [
                'attr' => ['class' => 'date-picker'],
            ])
            ->add('referenceNo',null)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WelfareBundle\Entity\MicroCreditPayment',
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
