<?php

namespace BudgetBundle\Form;

use AppBundle\Entity\Office;
use AppBundle\Form\Type\DateType;
use AppBundle\Subscriber\AddAttachmentsFieldSubscriber;
use BudgetBundle\Form\Type\BudgetHeadType;
use BudgetBundle\Validator\Constraints\CheckMaxExpense;
use Devnet\PolicyManagerBundle\Transformer\DateTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class BudgetExpenseForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('budgetHead', BudgetHeadType::class, [
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
                new GreaterThan(['value' => 0]),
                new CheckMaxExpense(['budgetExpense' => $options['data']])
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
            'data_class' => 'BudgetBundle\Entity\BudgetExpense'
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
