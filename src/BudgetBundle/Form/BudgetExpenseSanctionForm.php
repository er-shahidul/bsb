<?php

namespace BudgetBundle\Form;

use AppBundle\Entity\Office;
use AppBundle\Form\Type\DateType;
use BudgetBundle\Form\Type\BudgetHeadType;
use Devnet\PolicyManagerBundle\Transformer\DateTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class BudgetExpenseSanctionForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('totalAmount', null, [
            'label' => 'Amount Passed',
            'required' => false,
            'constraints' => [
                new NotBlank()
            ],
            'attr' => ['class' => 'amount input-small']
        ]);

        $builder->add('chequeLipiNo', null, [
            'label' => 'Cheque Lipi No',
            'required' => false,
            'constraints' => [
                new NotBlank()
            ],
            'attr' => ['class' => 'input-medium']
        ]);
        $builder->add('chequeLipiDate', DateType::class, [
            'label' => 'Cheque Lipi Date',
            'required' => false,
            'constraints' => [
                new NotBlank()
            ]
        ]);
        $builder->add('save', SubmitType::class, [
            'label' => 'Save',
            'attr' => ['class' => 'btn green']]
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BudgetBundle\Entity\BudgetExpenseSanction'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'budgetbundle_budgetexpensesanction';
    }
}
