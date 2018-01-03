<?php

namespace BudgetBundle\Form\ReportForm;

use AppBundle\Form\ReportForm\BaseReportForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BudgetRequestReportForm extends BaseReportForm
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('section', TextType::class, [
                'label' => 'মন্ত্রণালয়/বিভাগ'
            ])
            ->add('institute', TextType::class, [
                'label' => 'প্রতিষ্ঠান'
            ])
            ->add('unit', TextType::class, [
                'label' => 'পরিচালন ইউনিট'
            ]);
    }
}
