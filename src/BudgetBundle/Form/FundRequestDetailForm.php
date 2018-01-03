<?php

namespace BudgetBundle\Form;

use AppBundle\Entity\Office;
use AppBundle\Form\Type\DateType;
use BudgetBundle\Entity\BudgetDetail;
use BudgetBundle\Form\Type\BudgetHeadType;
use Devnet\PolicyManagerBundle\Transformer\DateTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class FundRequestDetailForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('requestAmount');
        $builder->add('remark');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BudgetDetail::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'budgetbundle_fundrequestdetails';
    }
}
