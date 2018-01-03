<?php

namespace BudgetBundle\Policy;

use Devnet\PolicyManagerBundle\Policy\PolicyGroupInterface;
use Devnet\PolicyManagerBundle\Transformer\DateTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactory;

class Budget implements PolicyGroupInterface
{

    public function getForm(FormFactory $formFactory, $data = null)
    {
        $builder = $formFactory->createBuilder(FormType::class, $data);

        $builder->add('last_date_of_budget_submission', DateType::class, [
            'widget' => 'single_text',
            'html5' => false,
            'attr' => [
                'class' => 'date-picker',
            ]
        ]);

        $builder->add('last_date_of_amendment_submission', DateType::class, [
            'widget' => 'single_text',
            'html5' => false,
            'attr' => [
                'class' => 'date-picker',
            ]
        ]);

        $builder->add('last_date_of_budget_surrender', DateType::class, [
            'widget' => 'single_text',
            'html5' => false,
            'attr' => [
                'class' => 'date-picker',
            ]
        ]);

        DateTransformer::apply($builder->get('last_date_of_budget_submission'));
        DateTransformer::apply($builder->get('last_date_of_amendment_submission'));
        DateTransformer::apply($builder->get('last_date_of_budget_surrender'));

        return $builder->getForm();
    }

    public static function getLabel()
    {
        return 'Budget Policies';
    }

    public static function getNameSpace()
    {
        return 'budget';
    }
}
