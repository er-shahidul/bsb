<?php

namespace WelfareBundle\Policy;

use Devnet\PolicyManagerBundle\Policy\PolicyGroupInterface;
use Devnet\PolicyManagerBundle\Transformer\DateTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormFactory;

class WelfarePolicy implements PolicyGroupInterface
{

    public function getForm(FormFactory $formFactory, $data = null)
    {
        $builder = $formFactory->createBuilder(FormType::class, $data);

        $builder->add('bscr_eligibility', null, [
            'label' => 'BSCR: Eligibility',
            'mapped' => false,
            'disabled' => true,
            'data' => 'Ex-Bangladesh, Ex-British Servicemen & Spouse (if Servicemen is not alive)'
        ]);

        $builder->add('bscr_single_time_consecutive_year', NumberType::class, [
            'label' => 'BSCR: Single Time Welfare for Consecutive Year Factor',
            'attr' => [
                'class' => 'input-small  amount',
            ],
            'required' => true
        ]);

        $builder->add('bscr_maximum_sanction', NumberType::class, [
            'label' => 'BSCR: Maximum Sanction',
            'attr' => [
                'class' => 'input-medium  amount',
            ],
            'required' => true
        ]);

        $builder->add('bscr_maximum_sanction_for_special_case', NumberType::class, [
            'label' => 'BSCR: Maximum Sanction for Special Case',
            'attr' => [
                'class' => 'input-medium  amount',
            ],
            'required' => true
        ]);

        $builder->add('bscr_maximum_sanction_per_year', NumberType::class, [
            'label' => 'BSCR: Maximum Sanction Per Year',
            'attr' => [
                'class' => 'input-medium  amount',
            ],
            'required' => true
        ]);

        $builder->add('bscr_maximum_sanction_for_special_case_per_year', NumberType::class, [
            'label' => 'BSCR: Maximum Sanction for Special Case Per Year',
            'attr' => [
                'class' => 'input-medium  amount',
            ],
            'required' => true
        ]);

        $builder->add('rcel_eligibility', null, [
            'label' => 'RCEL: Eligibility',
            'mapped' => false,
            'disabled' => true,
            'data' => 'Ex-British Servicemen & Spouse (if Servicemen is not alive)'
        ]);

        $builder->add('rcel_grant_amount', NumberType::class, [
            'label' => 'RCEL: grant amount',
            'attr' => [
                'class' => 'input-medium amount',
            ],
            'required' => true
        ]);

        $builder->add('single_time_grant_deadline', DateType::class, [
            'label' => 'RCEL: Single Time Grant Deadline Date',
            'widget' => 'single_text',
            'html5' => false,
            'attr' => [
                'class' => 'date-picker input-medium',
            ],
            'required' => true
        ]);

        $builder->add('micro_credit_maximum_sanction', NumberType::class, [
            'label' => 'Micro-credit: Maximum Sanction',
            'attr' => [
                'class' => 'input-medium  amount',
            ],
            'required' => true
        ]);

        $builder->add('micro_credit_maximum_sanction_per_year', NumberType::class, [
            'label' => 'Micro-credit: Maximum Sanction Per Year',
            'attr' => [
                'class' => 'input-medium  amount',
            ],
            'required' => true
        ]);

        $builder->add('micro_credit_per_installment_amount', NumberType::class, [
            'label' => 'Micro-credit: Per Installment Amount',
            'attr' => [
                'class' => 'input-medium  amount',
            ],
            'required' => true
        ]);

        $builder->add('micro_credit_payment_free_month_count', NumberType::class, [
            'label' => 'Micro-credit: Number of Installment Free Months',
            'attr' => [
                'class' => 'input-medium  amount',
            ],
            'required' => true
        ]);


        DateTransformer::apply($builder->get('single_time_grant_deadline'));

        return $builder->getForm();
    }

    public static function getLabel()
    {
        return 'Welfare Policies';
    }

    public static function getNameSpace()
    {
        return 'welfare';
    }
}
