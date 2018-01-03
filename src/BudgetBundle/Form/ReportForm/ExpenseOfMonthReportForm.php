<?php

namespace BudgetBundle\Form\ReportForm;

use AppBundle\Form\ReportForm\BaseReportForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ExpenseOfMonthReportForm extends BaseReportForm
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('basb', TextType::class, [
                'label' => 'ডিএএসবি'
            ])
            ->add('letterNo', TextType::class, [
                'label' => 'পত্র নং'
            ])
            ->add('date', TextType::class, [
                'label' => 'তারিখ',
                'attr' => array(
                    'class' => 'date-picker',
                ),
            ]);
    }
}
