<?php
namespace WelfareBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BSCRMaxGrant extends Constraint
{
    public $message = 'Maximum grant amount limit: "%string%" TK.';
}