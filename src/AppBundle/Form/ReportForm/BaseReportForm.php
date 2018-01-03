<?php

namespace AppBundle\Form\ReportForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class BaseReportForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
     {
         $resolver->setDefaults(array(
             'method' => 'POST',
             'attr' => ['target' => '_blank']
         ));
     }
}
