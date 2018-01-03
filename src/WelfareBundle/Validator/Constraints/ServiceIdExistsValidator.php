<?php
namespace WelfareBundle\Validator\Constraints;

use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Devnet\PolicyManagerBundle\Repository\PolicyRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\RangeValidator;
use Symfony\Component\Validator\ConstraintValidator;

class ServiceIdExistsValidator extends RangeValidator
{
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        $exServiceMan = $this->em->getRepository('PersonnelBundle:ExServiceman')->findOneBy(['identityNumber' => $value]);
        if (!$exServiceMan) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

}