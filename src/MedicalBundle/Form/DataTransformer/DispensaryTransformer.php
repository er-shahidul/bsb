<?php

namespace MedicalBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use MedicalBundle\Entity\Dispensary;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DispensaryTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param mixed $dispensary
     * @return integer
     * @internal param ArrayCollection|null $sanctionEntry
     */
    public function transform($dispensary)
    {
        if ($dispensary === null) {
            return '';
        }

        return $dispensary->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param mixed $dispensaryId
     * @return Dispensary
     * @internal param array $sanctionEntryIds
     */
    public function reverseTransform($dispensaryId)
    {
        if (!$dispensaryId) {
            return;
        }

        $dispensary = $this->em->getRepository('MedicalBundle:Dispensary')->find($dispensaryId);


        if ($dispensary === null) {
            throw new TransformationFailedException(sprintf(
                'A Dispensary with number "%s" does not exist!',
                $dispensaryId
            ));
        }

        return $dispensary;
    }
}