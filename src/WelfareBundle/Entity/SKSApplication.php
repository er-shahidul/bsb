<?php

namespace WelfareBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use BoardMeetingBundle\Core\BoardMeetingRelationTrait;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PersonnelBundle\Entity\ExFamilyInformation;
use PersonnelBundle\Entity\ExServiceman;

/**
 * SKSApplication
 *
 * @ORM\Table(name="sks_applications")
 * @ORM\Entity(repositoryClass="WelfareBundle\Repository\SKSApplicationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 */
class SKSApplication extends BaseWelfareApplication
{
    /**
     * @var SKSApplicationType
     *
     * @ORM\ManyToOne(targetEntity="WelfareBundle\Entity\SKSApplicationType")
     */
    private $type;

    /**
     * @return SKSApplicationType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param SKSApplicationType $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }


}

