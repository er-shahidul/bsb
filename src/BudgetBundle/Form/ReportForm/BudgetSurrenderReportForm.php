<?php

namespace BudgetBundle\Form\ReportForm;

use AppBundle\Form\ReportForm\BaseReportForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BudgetSurrenderReportForm extends BaseReportForm
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('potro_no', TextType::class, [
                'label' => 'পত্র নং'
            ])
            ->add('mod_potro_no', TextType::class, [
                'label' => 'প্রতিরক্ষা মন্ত্রণালয় পত্র নং'
            ])
            ->add('mod_potro_date', TextType::class, [
                'label' => 'তারিখ'
            ])
            ->add('prapok_designation', TextType::class, [
                'label' => 'প্রাপকের পদবি'
            ]);
    }
}
