<?php
namespace WelfareBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ServiceIdExists extends Constraint
{
    public $message = 'Soldier ID not found.';
}