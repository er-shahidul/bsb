<?php

namespace BudgetBundle\Form;

use AppBundle\Form\Type\DateType;
use AppBundle\Subscriber\AddAttachmentsFieldSubscriber;
use BudgetBundle\Form\Type\BudgetIncomeHeadType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class BudgetIncomeForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('budgetHead', BudgetIncomeHeadType::class, [
            'required' => false,
            'constraints' => [
                new NotBlank()
            ]
        ]);
        $builder->add('letterNo', null, [
            'required' => false,
            'constraints' => [
                new NotBlank()
            ]
        ]);
        $builder->add('letterDate', DateType::class, [
            'required' => false,
            'constraints' => [
                new NotBlank()
            ]
        ]);
        $builder->add('amount', null, [
            'attr' => ['class' => 'input-small amount'],
            'required' => false,
            'constraints' => [
                new NotBlank(),
                new GreaterThan(['value' => 0])
            ]
        ]);
        $builder->add('description', null, ['required' => false]);

        $builder->add('save', SubmitType::class, [
            'label' => 'Save',
            'attr' => ['class' => 'btn blue']]
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BudgetBundle\Entity\BudgetIncome'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'budgetbundle_budgetexpense';
    }
}
