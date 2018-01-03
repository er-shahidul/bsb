<?php
namespace WelfareBundle\Validator\Constraints;

use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Devnet\PolicyManagerBundle\Repository\PolicyRepository;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\RangeValidator;
use Symfony\Component\Validator\ConstraintValidator;

class BSCRMaxGrantValidator extends RangeValidator
{
    private $policyRepository;

    /**
     * BSCRMaxGrantValidator constructor.
     * @param PolicyRepository $policyRepository
     */
    public function __construct(PolicyRepository $policyRepository)
    {
        $this->policyRepository = $policyRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        $maxGrant = $this->policyRepository->getPolicyValue('welfare.bscr_maximum_sanction_for_special_case');

        parent::validate($value, new Range(array(
            'max' => $maxGrant,
            'maxMessage' => sprintf("Maximum grant amount limit: %s TK.", number_format($maxGrant))
            ))
        );
    }

}