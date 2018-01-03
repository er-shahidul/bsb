<?php

namespace BudgetBundle\Form;

use AppBundle\Entity\Office;
use AppBundle\Form\Type\DateType;
use BudgetBundle\Entity\Budget;
use BudgetBundle\Form\Type\BudgetHeadType;
use Devnet\PolicyManagerBundle\Transformer\DateTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class FundRequestForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('budgetDetails', CollectionType::class, [
            'entry_type' => FundRequestDetailForm::class,
            'allow_add'    => true,
            'allow_delete' => true,
            'by_reference' => false,
            'prototype'    => true,
        ]);

        $builder->add('save', SubmitType::class, [
            'attr' => ['class' => 'btn green']
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Budget::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'budgetbundle_fundrequest';
    }
}
