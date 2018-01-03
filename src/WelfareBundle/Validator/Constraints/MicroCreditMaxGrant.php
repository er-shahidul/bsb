<?php
namespace WelfareBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MicroCreditMaxGrant extends Constraint
{
    public $message = 'Maximum grant amount limit: "%string%" TK.';
}