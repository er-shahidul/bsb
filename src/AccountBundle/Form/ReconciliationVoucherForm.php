<?php

namespace AccountBundle\Form;

use AppBundle\Form\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReconciliationVoucherForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('markForReconcile', CheckboxType::class, [
            'required' => false,
            'label' => 'Yes',
            'label_attr' => [
                'class' => 'mt-checkbox mt-checkbox-outline'
            ]
        ]);
        $builder->add('reconciliationDate', DateType::class, [
            'required' => false
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\Voucher',
            'add_attachment' => false,
            'translation_domain' => false
        ));
    }
}
