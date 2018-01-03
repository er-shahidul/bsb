<?php

namespace AccountBundle\Form\DataTransformer;

use AccountBundle\Entity\SanctionEntry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class SanctionEntryTransformer implements DataTransformerInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param ArrayCollection|null $sanctionEntry
     * @return array
     */
    public function transform($dispensary)
    {
        if (!method_exists($dispensary, 'getOwner')) {
            return null;
        }

        $data = [];

        foreach ($dispensary->getOwner()->getSanctions() as $sanction) {
            $data[] = $sanction->getId();
        }
        return $data;
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  array $sanctionEntryIds
     * @return ArrayCollection
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($sanctionEntryIds)
    {
        $data = new ArrayCollection();

        if (empty($sanctionEntryIds)) {
            return $data;
        }

        foreach ($sanctionEntryIds as $id) {

            if ($sanctionEntry = $this->em->getRepository(SanctionEntry::class)->find($id)) {
                $data->add($sanctionEntry);
            } else {

                throw new TransformationFailedException(sprintf(
                    'An SanctionEntry with number "%s" does not exist!',
                    $$id
                ));
            }
        }

        return $data;
    }
}