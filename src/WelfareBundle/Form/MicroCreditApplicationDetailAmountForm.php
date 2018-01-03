<?php

namespace WelfareBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MicroCreditApplicationDetailAmountForm extends AbstractType
{
    private $installmentAmount = 0;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->installmentAmount = $options['installmentAmount'];

        $builder
            ->add('installmentAmount', NumberType::class, [
                    'label' => 'Installment Amount',
                    'attr' => ['class' => 'input-small grant-amount'],
                    'data' => $this->installmentAmount
                ])
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WelfareBundle\Entity\MicroCreditApplicationDetail',
            'installmentAmount' => 0,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'welfarebundle_microcreditapplication_detail';
    }
}
