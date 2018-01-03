<?php

namespace Devnet\PolicyManagerBundle\Transformer;


use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;

class DateTransformer
{

    public static function apply(FormBuilderInterface $formField){
        $formField->addModelTransformer(new CallbackTransformer(
            function ($value) {
                return new \DateTime($value);
            },
            function ($value) {
                return $value->format('Y-m-d');
            }
        ));

//        $formField->addViewTransformer(new CallbackTransformer(
//            function ($value) {
//                return $value;
//            },
//            function ($value) {
//                return $value;
//            }
//        ));
    }
}